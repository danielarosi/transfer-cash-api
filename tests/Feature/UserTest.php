<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Teste listagem de usuários
     *
     * @return void
     */
    public function testGetAllUsersSuccess()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    public function testShowUserSuccess() {

        $response = $this->get('/api/users/1');

        $response->assertStatus(200);        
    }
    
    public function testShowUserNotExist() {

        $response = $this->get('/api/users/555');

        $response->assertStatus(404);        
    }

    public function testCreateUserSuccess()
    {
        DB::beginTransaction();

        $dataRquest = [
            'fullname' => 'Fulano de Tal',
            'cpf_cnpj' => '12345678958',
            'email' => 'fulanodetal2@gmail.com',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];

        $response = $this->post('/api/users/', $dataRquest);

        $response->assertStatus(201);  

        DB::rollBack();
    }

    public function testCreateWithBadRequestResponse()
    {
        DB::beginTransaction();

        $dataRquest = [
            'fullname' => 'Fulano de Tal',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];
        
        $response = $this->post('/api/users/', $dataRquest);
        
        $response->assertStatus(400);  

        DB::rollBack();
    }

    public function testCreateUserValidationEmailSuccess()
    {
        DB::beginTransaction();

        $dataRquest = [
            'fullname' => 'Fulano de Tal',
            'cpf_cnpj' => '12345678958',
            'email' => 'maria.silva@gmail.com',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];

        $response = $this->post('/api/users/', $dataRquest);

        $response->assertStatus(400);  

        DB::rollBack();
    }
    public function testCreateUserValidationCPFCnpjSuccess()
    {
        DB::beginTransaction();

        $dataRquest = [
            'fullname' => 'Fulano de Tal',
            'cpf_cnpj' => '103.600.162-86',
            'email' => 'fulanodetal2@gmail.com',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];

        $response = $this->post('/api/users/', $dataRquest);

        $response->assertStatus(400);  

        DB::rollBack();
    }
}
