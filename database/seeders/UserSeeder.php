<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'fullname' => 'João da Silva',
            'cpf_cnpj' => '326.457.013-02',
            'email' => 'joao@gmail.com',
            'password' => Hash::make(value: 'password'),
            'user_types_id' => 1,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'fullname' => 'Maria da Silva',
            'cpf_cnpj' => '103.600.162-86',
            'email' => 'maria.silva@gmail.com',
            'password' => Hash::make(value: 'password'),
            'user_types_id' => 1,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'fullname' => 'Eduardo Rodrigues',
            'cpf_cnpj' => '142.920.145-23',
            'email' => 'eduardo.rodrigues@gmail.com',
            'password' => Hash::make(value: 'password'),
            'user_types_id' => 1,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'fullname' => 'Juliano Brasil',
            'cpf_cnpj' => '466.082.684-83',
            'email' => 'juliano.brasil@gmail.com',
            'password' => Hash::make(value: 'password'),
            'user_types_id' => 1,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'fullname' => 'Rafaela Rodrigues',
            'cpf_cnpj' => '553.276.021-76',
            'email' => 'rafaela.rodrigues@gmail.com',
            'password' => Hash::make(value: 'password'),
            'user_types_id' => 1,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'fullname' => 'Lojas Duilson',
            'cpf_cnpj' => '48.126.061/9710-59',
            'email' => Str::random(length: 10).'@gmail.com',
            'password' => Hash::make(value: 'password'),
            'user_types_id' => 2,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'fullname' => 'Shein S/A',
            'cpf_cnpj' => '94.557.763/5765-63',
            'email' => Str::random(length: 10).'@gmail.com',
            'password' => Hash::make(value: 'password'),
            'user_types_id' => 2,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'fullname' => 'Renner S/A',
            'cpf_cnpj' => '63.601.127/6589-63',
            'email' => Str::random(length: 10).'@gmail.com',
            'password' => Hash::make(value: 'password'),
            'user_types_id' => 2,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'fullname' => 'Boticario',
            'cpf_cnpj' => '54.123.827/9795-33',
            'email' => Str::random(length: 10).'@gmail.com',
            'password' => Hash::make(value: 'password'),
            'user_types_id' => 2,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('users')->insert([
            'fullname' => 'Açai Atacadão',
            'cpf_cnpj' =>'75.575.203/7222-40',
            'email' => Str::random(length: 10).'@gmail.com',
            'password' => Hash::make(value: 'password'),
            'user_types_id' => 2,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
    }
}
