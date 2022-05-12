<?php

namespace App\Services;

use App\Exceptions\{
    AccountNotFoundException,
    BalanceException,
    TransactionNotCreatedException,
    TransactionNotFoundException,
    UserCannotBeLojistaExpcetion,
    UserNotFoundException
};
use App\Repositories\{
    AccountRepository,
    TransactionRepository,
    UserRepository
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Classe responsável pela tratativa das regras de negócio referente as Transações
 */
class TransactionService implements TransactionInterface
{
    protected $transactionRepository;
    protected $userRepository;
    protected $accountRepository;
    /**
     * Cria uma nova instância de AccountRepository, TransactionRepository e UserRepository
     * 
     * @param TransactionRepository $transactionRepository Injeção de dependência da camada de repositório
     * @param UserRepository $userRepository Injeção de dependência da camada de repositório
     * @param AccountRepository $accountRepository Injeção de dependência da camada de repositório
     */
    public function __construct(TransactionRepository $transactionRepository, UserRepository $userRepository, AccountRepository $accountRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->userRepostitory = $userRepository;
        $this->accountRepository = $accountRepository;
    }
    /**
     * Busca todas as transações, transformando source_id e target_id
     * em payer_id e payee_id
     * 
     * @return array
     */
    public function all()
    {
        $transactions = $this->transactionRepository->all();

        $dataList = array();
        foreach ($transactions as $transaction) {

            $dataList[] = [
                'id' => $transaction->id,
                'initial_balance_payer' => $transaction->initial_balance_source,
                'initial_balance_payee' => $transaction->initial_balance_target,
                'transferred_value' => $transaction->transferred_value,
                'payer_id' => $transaction->source_id,
                'payee_id' => $transaction->target_id,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at,
            ];
        }
        return $transactions;
    }
    /**
     * Busca todas as transações de um determinado usuário.
     * 
     * @param String $column
     * @param int $id
     * 
     * @return array
     */
    public function show(String $column, int $id)
    {
        $transactions = $this->transactionRepository->findAllByColumn($column, $id);
        if ($transactions->isEmpty()) {
            throw new TransactionNotFoundException('A transação não foi encontrada');
        }
        $dataList = array();
        foreach ($transactions as $transaction) {

            $dataList[] = [

                'id' => $transaction->id,
                'initial_balance_payer' => $transaction->initial_balance_source,
                'initial_balance_payee' => $transaction->initial_balance_target,
                'transferred_value' => $transaction->transferred_value,
                'payer_id' => $transaction->source_id,
                'payee_id' => $transaction->target_id,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at,
            ];
        }
        return $dataList;
    }

    /**
     * Cria uma transação entre usuários, considerando que cada usuário
     * tem uma conta e que o usuário de origem sempre será um usuário
     * comum e que o usuário terá saldo. Caso contrário uma exceção é
     * disparada para cada violação.
     * 
     * @param Request $request
     * 
     * @return json
     * 
     * @throws UserNotFoundException           Se não existir usuário 
     * @throws AccountNotFoundException        Se o usuário não tiver uma conta cadastrada
     * @throws UserCannotBeLojistaExpcetion    Se o usuário pagador não for um Lojista
     * @throws BalanceException                Se o usuário está autorizado e se tem saldo suficiente
     * @throws TransactionNotCreatedException  Se houver um erro ao persistir a transação
     */
    public function store(Request $request)
    {
        //Avalia se o usuário Pagador existe na base de dados;
        $payer = $this->userRepostitory->find(intval($request->payer))->first();
        if (empty($payer)) {
            throw new UserNotFoundException('O usuário pagador não foi encontrado');
        }

        //Avalia se o usuário pagador tem conta
        $accountPayer = $this->accountRepository->findFirstByColumn('user_id', intval($request->payer));
        if (empty($accountPayer)) {
            throw new AccountNotFoundException('O usuário pagador não tem conta');
        }

        //Avalia se o usuário Beneficiário existe na base de dados;
        $payee =  $this->userRepostitory->find(intval($request->payee))->first();
        if (empty($payee)) {
            throw new UserNotFoundException('O usuário beficiário não foi encontrado');
        }

        //Avalia se o usuário beneficiário tem conta
        $accountPayee = $this->accountRepository->findFirstByColumn('user_id', intval($request->payee));
        if (empty($accountPayee)) {
            throw new AccountNotFoundException('O usuário beficiário não tem conta');
        }

        //Avalia se o usuário é Lojista e pagador (violação)
        $userTypeId = $payer->user_types_id;
        if ($userTypeId == 2) {
            throw new UserCannotBeLojistaExpcetion('O usuário pagador não pode ser um Lojista');
        }

        //Avalia se o saldo é suficiente para a transferência
        $balancePayer = $accountPayer->balance;
        if ($balancePayer - $request->value < 0) {
            throw new BalanceException('O usuário não tem saldo para fazer a transferência');
        }

        //Avalia se o usuário está autorizado a fazer transferência
        if (!$this->isAuthorizedCallout()) {
            throw new BalanceException('O usuário não está autorizado a fazer a transferência');
        }

        $data = [
            'source_id' => $accountPayer->id,
            'target_id' => $accountPayee->id,
            'transferred_value' => $request->value,
            'initial_balance_source' => $accountPayer->balance,
            'initial_balance_target' => $accountPayee->balance
        ];
        try {
            DB::beginTransaction();
            $accountPayer->balance -= $request->value;
            $accountPayee->balance += $request->value;
            $isBalancePayerUpdated = $accountPayer->save();
            $isTransactionCreated = $this->transactionRepository->save($data);
            $notified = $this->isNotificationSent();

            $isBalancePayeeUpdated = $accountPayee->save();
            if (
                $isTransactionCreated &&
                $isBalancePayeeUpdated &&
                $isBalancePayerUpdated &&
                $notified
            ) {
                DB::commit();
            } else {
                DB::rollBack();
                //422 Unprocessable Entity
                throw new TransactionNotCreatedException('Não foi possível efetuar a transferência');
            }
        } catch (Exception $e) {
            DB::rollBack();
            //422 Unprocessable Entity
            throw new TransactionNotCreatedException('Não foi possível efetuar a transferência');
        }
    }
    /**
     * Mock para simulação de consulta a um serviço autorizador
     * 
     * @return bool
     */
    public function isAuthorizedCallout()
    {
        $url = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';
        $response = $this->calloutResponse($url);
        if ($response['message'] == 'Autorizado') {
            return true;
        }
        return false;
    }
    /**
     * Simulação de envio de email/sms notificando o Payer sobre recebimento
     * 
     * @return bool
     */
    public function isNotificationSent()
    {
        $url = 'http://o4d9z.mocklab.io/notify';
        $response = $this->calloutResponse($url);
        if ($response == null) {
            return false;
        }
        if ($response['message'] == 'Success') {
            return true;
        }
        return false;
    }
    /**
     * chamada url
     * 
     * @return json
     */
    private function calloutResponse($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl, CURLOPT_TIMEOUT, 35);
        $result = curl_exec($curl);
        $response = json_decode($result, true);
        curl_close($curl);
        return $response;
    }
}
