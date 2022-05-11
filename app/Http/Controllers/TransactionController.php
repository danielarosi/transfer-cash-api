<?php

namespace App\Http\Controllers;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\BalanceException;
use App\Exceptions\TransactionNotCreatedException;
use App\Exceptions\TransactionNotFoundException;
use App\Exceptions\UserCannotBeLojistaExpcetion;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Response\{ErrorResponse, SuccessResponse};
use App\Services\{TransactionService, UserService};
use Exception;
use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

/**
 * Classe que implementa Controle das Transações
 */
class TransactionController extends Controller
{
    private $transactionService;
    /**
     * Cria uma nova instância de TransactionService
     * 
     * @param TransactionService $transactionService Injeção de dependência da camada de repositório
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    /**
     * Lista todas as transações
     * 
     * @return json
     */
    public function index()
    {
        $transaction = $this->transactionService->all();

        return SuccessResponse::ok($transaction, null, 200);
    }
    /**
     * Busca uma transação por Pagador ou Beneficiário
     * 
     * @param String $column
     * @param int $id
     * 
     * @return object
     */
    public function show($column, $id)
    {
        try {

            $transaction = $this->transactionService->show($column, $id);

            return SuccessResponse::ok($transaction, null, 200);
        } catch (TransactionNotFoundException $e) {

            return ErrorResponse::fails($e->getMessage(), 404,);
        }
    }
    /**
     * Valida campos conforme regra, e salva uma transação conforme payload enviado
     * 
     * @param Request $request
     * 
     * @return object
     */
    public function store(Request $request)
    {
        $rules = [
            'payer' => 'required',
            'payee' => 'required',
            'value' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules, $messages = [
            'required' => 'O campo de nome :attribute é obrigatório.',
        ]);

        if ($validator->fails())
            return ErrorResponse::failsValidator($validator->errors(), 400);

        try {

            $this->transactionService->store($request);

            return SuccessResponse::ok(null, 'Transferência realizada.', 201);
        } catch (UserNotFoundException $e) {

            return ErrorResponse::fails($e->getMessage(), 404);
        } catch (AccountNotFoundException $e) {

            return ErrorResponse::fails($e->getMessage(), 404);
        } catch (UserCannotBeLojistaExpcetion $e) {

            return ErrorResponse::fails($e->getMessage(), 404);
        } catch (BalanceException $e) {

            return ErrorResponse::fails($e->getMessage(), 401);
        } catch (TransactionNotCreatedException $e) {

            return ErrorResponse::fails($e->getMessage(), 422);
        }
    }
}
