<?php

namespace App\Services;

use App\Exceptions\{UserNotCreatedException, UsersNotFoundException};
use App\Repositories\{AccountRepository, UserRepository};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Classe responsável pela tratativa das regras de negócio referente aos usuários
 */
class UserService implements UserInterface
{
    protected $userRepository;
    protected $accountRepository;

    /**
     * Cria uma nova instância de UserRepository e AccountRepository
     * 
     * @param UserRepository $userRepository Injeção de dependência da camada de repositório
     * @param AccountRepository $accountRepository Injeção de dependência da camada de repositório
     */
    public function __construct(UserRepository $userRepository, AccountRepository $accountRepository)
    {
        $this->userRepository = $userRepository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Busca todos os usuários
     * 
     * @return Array
     */
    public function all()
    {
        $users = $this->userRepository->all();
        if ($users->isEmpty()) {
            throw new UsersNotFoundException('Usuários não encontrados.');
        }
        return $users->toArray();
    }
    /**
     * Busca usuários por id
     * 
     * @param int $id
     * 
     * @return object
     */
    public function show(int $id)
    {
        $user = $this->userRepository->find($id)->first();
        if (empty($user)) {
            throw new UsersNotFoundException('Usuários não encontrados.');
        }
        return $user;
    }
    /**
     * Cria um usuário e uma conta associada a ele.
     * 
     * @param Request $request
     * 
     * @return object
     * 
     * @throws UserNotCreatedException  Se houver um erro ao persistir o usuário
     */
    public function store(Request $request)
    {
        try {
            $dataAccount['balance'] = $request->initial_balance;
            $data = $request->except('initial_balance');
            //Tratar senha
            $data['password'] = bcrypt($request->password);

            DB::beginTransaction();

            if ($user = $this->userRepository->save($data)) {
                $account = $this->accountRepository->findFirstByColumn('user_id', $user->id);

                if (!$account) {
                    $dataAccount['user_id'] = $user->id;
                    if ($account = $this->accountRepository->save($dataAccount)) {
                        DB::commit();
                    } else {
                        DB::rollBack();
                        //422 Unprocessable Entity
                        throw new UserNotCreatedException("Erro ao inserir o usuário");
                    }
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            //422 Unprocessable Entity
            throw new UserNotCreatedException("Erro ao inserir o usuário");
        }

        $responseData = [
            'id' => $user->id,
            'fullname' => $user->fullname,
            'cpf_cnpj' => $user->cpf_cnpj,
            'email' => $user->email,
            'user_types_id' => $user->user_types_id,
            'account_id' => $account->id,
            'balance' => $account->balance,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        return $responseData;
    }
}
