<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Classe responsável pela operação de persistência 
 */
class UserRepository extends BaseRepository
{
    protected $user;

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function all(): object
    {
        $users = DB::table('users')
            ->leftJoin('accounts', 'users.id', '=', 'accounts.user_id')
            ->select(
                'users.id',
                'users.fullname',
                'users.cpf_cnpj',
                'users.email',
                'users.user_types_id',
                'users.created_at',
                'accounts.id as account_id',
                'accounts.balance',
            )
            ->get();
            
        return $users;
    }
    public function find(int $id): object
    {
        $user = DB::table('users')
            ->leftJoin('accounts', 'users.id', '=', 'accounts.user_id')
            ->select(
                'users.id',
                'users.fullname',
                'users.cpf_cnpj',
                'users.email',
                'users.user_types_id',
                'users.created_at',
                'accounts.id as account_id',
                'accounts.balance',
            )
            ->where('users.id', $id)
            ->get();

        return $user;
    }
}
