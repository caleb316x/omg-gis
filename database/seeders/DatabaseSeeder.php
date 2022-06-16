<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'first_name' => 'Ching',
            'middle_name' => 'X',
            'last_name' => 'Ortega',
            'role' => 0,
            'password' => Hash::make('secret123')
        ]);

        DB::table('users')->insert([
            'username' => 'frontdesk',
            'first_name' => 'frontdesk',
            'middle_name' => 'X',
            'last_name' => 'frontdesk',
            'role' => 1,
            'password' => Hash::make('secret123')
        ]);

        DB::table('users')->insert([
            'username' => 'cashier',
            'first_name' => 'cashier',
            'middle_name' => 'X',
            'last_name' => 'cashier',
            'role' => 2,
            'password' => Hash::make('secret123')
        ]);
    }
}
