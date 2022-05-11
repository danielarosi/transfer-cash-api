<?php
namespace App\Repositories;

use App\Models\Account;

class AccountRepository extends BaseRepository
{
    protected $account;

    public function __construct(Account $account)
    {
        parent::__construct($account);
    }

   
}
