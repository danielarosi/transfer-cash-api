<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            'balance' => rand(1, 12500) / 10,
            'user_id' => 1,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('accounts')->insert([
            'balance' => rand(1, 12500) / 10,
            'user_id' => 2,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('accounts')->insert([
            'balance' => rand(1, 12500) / 10,
            'user_id' => 3,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('accounts')->insert([
            'balance' => rand(1, 12500) / 10,
            'user_id' => 4,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('accounts')->insert([
            'balance' => rand(1, 12500) / 10,
            'user_id' => 5,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('accounts')->insert([
            'balance' => rand(1, 12500) / 10,
            'user_id' => 6,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('accounts')->insert([
            'balance' => rand(1, 12500) / 10,
            'user_id' => 7,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('accounts')->insert([
            'balance' => rand(1, 12500) / 10,
            'user_id' => 8,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('accounts')->insert([
            'balance' => rand(1, 12500) / 10,
            'user_id' => 9,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('accounts')->insert([
            'balance' => rand(1, 12500) / 10,
            'user_id' => 10,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
    }
}
