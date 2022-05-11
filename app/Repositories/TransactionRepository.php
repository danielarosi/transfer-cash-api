<?php
namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        parent::__construct($transaction);
    }

}
