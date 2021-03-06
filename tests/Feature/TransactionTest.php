<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAllTransactionsSuccess()
    {
        $response = $this->get('/api/transactions');

        $response->assertStatus(200);
    }

    public function testShowTransactionPayerSuccess()
    {
        $response = $this->get('/api/transactions/source_id/1');

        $response->assertStatus(200);
    }

    public function testShowTransactionPayerNotExist()
    {
        $response = $this->get('/api/transactions/source_id/111');

        $response->assertStatus(404);
    }
    
    public function testShowTransactionPayeeSuccess()
    {
        $response = $this->get('/api/transactions/target_id/9');

        $response->assertStatus(200);
    }

    public function testShowTransactionPayeeNotExist()
    {
        $response = $this->get('/api/transactions/target_id/111');

        $response->assertStatus(404);
    }

    public function testCreateTransactionSuccess()
    {
        DB::beginTransaction();

        $dataRquest = [
            "value" => 100.00,
            "payer" => 3,
            "payee" => 9
        ];

        $response = $this->post('/api/transactions/', $dataRquest);

        $response->assertStatus(201);

        DB::rollBack();
    }

    public function testCreateTransactionWithPayerNoExists()
    {
        DB::beginTransaction();

        $dataRquest = [
            "value" => 100.00,
            "payer" => 97,
            "payee" => 9
        ];

        $response = $this->post('/api/transactions/', $dataRquest);

        $response->assertStatus(404);

        DB::rollBack();
    }

    public function testCreateTransactionWithPayeeNoExists()
    {
        DB::beginTransaction();

        $dataRquest = [
            "value" => 100.00,
            "payer" => 1,
            "payee" => 97
        ];

        $response = $this->post('/api/transactions/', $dataRquest);

        $response->assertStatus(404);

        DB::rollBack();
    }

    public function testCreateTransactionWithPayerHasNoAccount()
    {
        DB::beginTransaction();

        $dataRquest = [
            "value" => 100.00,
            "payer" => 2,
            "payee" => 1
        ];

        $response = $this->post('/api/transactions/', $dataRquest);

        $response->assertStatus(404);

        DB::rollBack();
    }

    public function testCreateTransactionWithPayeeHasNoAccount()
    {
        DB::beginTransaction();

        $dataRquest = [
            "value" => 100.00,
            "payer" => 1,
            "payee" => 10
        ];

        $response = $this->post('/api/transactions/', $dataRquest);

        $response->assertStatus(404);

        DB::rollBack();
    }

    public function testCreateTransactionPayeerCannotBeLojista()
    {
        DB::beginTransaction();

        $dataRquest = [
            "value" => 100.00,
            "payer" => 8,
            "payee" => 6
        ];

        $response = $this->post('/api/transactions/', $dataRquest);

        $response->assertStatus(404);

        DB::rollBack();
    }
}