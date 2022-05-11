<?php

namespace App\Http\Controllers;

use App\Exceptions\{NoUsersException, UserNotCreatedException};
use App\Http\Controllers\Response\{ErrorResponse, SuccessResponse};
use App\Services\UserInterface;
use Illuminate\{
    Support\Facades\Validator,
    Http\Request
};

/**
 * Classe que implementa Controle dos Usuários
 */
class UserController extends Controller
{

    protected $userService;
    /**
     * Cria uma nova instância de TransactionService
     * 
     * @param UserInterface $userService Injeção de dependência da camada de repositório
     */
    public function __construct(UserInterface $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Lista todos os usuários
     * 
     * @param Request $request
     * 
     * @return json
     */
    public function index(Request $request)
    {
        try {
            $user = $this->userService->all();
            return SuccessResponse::ok($user, null, 200);
        } catch (NoUsersException $e) {
            return ErrorResponse::fails($e->getMessage(), 401);
        }
    }
    /**
     * Busca usuário por ID
     * 
     * @param int $id
     * 
     * @return json
     */
    public function show($id)
    {
        try {
            $user = $this->userService->show($id);
            return SuccessResponse::ok($user, null, 200);
        } catch (NoUsersException $e) {
            return ErrorResponse::fails($e->getMessage(), 404);
        }
    }
    /**
     * Salva usuário
     * 
     * @param Request $request
     * 
     * @return json
     */
    public function store(Request $request)
    {
        //Rega de validação dos campos da requisição 
        $rules = [
            'fullname' => 'required:users',
            'email' => 'required|unique:users',
            'cpf_cnpj' => 'required|unique:users',
            'password' => 'required',
            'initial_balance' => 'required',
            'user_types_id' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules, $messages = [
            'required' => 'O campo de nome :attribute é obrigatório.',
            'unique'   => 'O :attribute já existe.',
        ]);
        if ($validator->fails())
            return ErrorResponse::failsValidator($validator->errors(), 400);

        try {
            $responseData = $this->userService->store($request);
            return SuccessResponse::ok($responseData, "Registro criado.", 201);
        } catch (UserNotCreatedException $e) {
            return ErrorResponse::fails($e->getMessage(), 422);
        }
    }
}
