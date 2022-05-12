<?php

namespace Tests\Feature;

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
            'cpf_cnpj' => '123.456.789-58',
            'email' => 'fulanodetal2@gmail.com',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];

        $response = $this->post('/api/users/', $dataRquest);

        $response->assertStatus(201);  

        DB::rollBack();
    }

    public function testCreateTwoUsersWithDifferentEmailsAndCpfSuccess()
    {
        DB::beginTransaction();

        $firstDataRquest = [
            'fullname' => 'Fulano de Tal',
            'cpf_cnpj' => '123.456.789-58',
            'email' => 'fulanodetal2@gmail.com',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];

        $firstResponse = $this->post('/api/users/', $firstDataRquest);

        $firstResponse->assertStatus(201);  

        $secondDataRquest = [
            'fullname' => 'Fulano de Tal',
            'cpf_cnpj' => '458.567.895-88',
            'email' => 'fulanodetal3@gmail.com',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];

        $secondResponse = $this->post('/api/users/', $secondDataRquest);

        $secondResponse->assertStatus(201);  

        DB::rollBack();
    }

    public function testCreateUserValidationEmailSuccess()
    {
        DB::beginTransaction();

        $dataRquest = [
            'fullname' => 'Jussara da Silva',
            'cpf_cnpj' => '123.456.788-75',
            'email' => 'jussara.pereira@gmail.com',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];

        $response = $this->post('/api/users/', $dataRquest);
        
        $response->assertStatus(201);
        
        $dataRquestSameEmail = [
            'fullname' => 'Jussara Pereira da Silva',
            'cpf_cnpj' => '444.456.789-83',
            'email' => 'jussara.pereira@gmail.com',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];

        $responseError = $this->post('/api/users/', $dataRquestSameEmail);
        
        $responseError->assertStatus(400);  
        DB::rollBack();

    }
    public function testCreateUserValidationCpfCnpjSuccess()
    {
        DB::beginTransaction();

        $dataRquest = [
            'fullname' => 'Rodrigo Pereira da Silva',
            'cpf_cnpj' => '104.601.162-86',
            'email' => 'rodrigo.pereira.silva@gmail.com',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];

        $response = $this->post('/api/users/', $dataRquest);
        
        $response->assertStatus(201);  
        
        $dataRquestSameCPF = [
            'fullname' => 'Rodrigo Pereira da Silva',
            'cpf_cnpj' => '104.601.162-86',
            'email' => 'rodrigo.ps@gmail.com',
            'password' => '12345678',
            'user_types_id' => '1',
            'initial_balance' => '10.0'
        ];

        $responseError = $this->post('/api/users/', $dataRquestSameCPF);
        
        $responseError->assertStatus(400);  
        
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
}
