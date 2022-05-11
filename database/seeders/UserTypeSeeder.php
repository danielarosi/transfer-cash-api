<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')->insert([
            'description' => 'COMUM',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
        DB::table('user_types')->insert([
            'description' => 'LOJISTA',
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
    }
}
