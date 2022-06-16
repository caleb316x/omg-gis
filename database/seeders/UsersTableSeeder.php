<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder {

    public function run()
    {
        User::create(array('username' => 'Admin','first_name' => 'Ching','middle_name' => 'X','last_name' => 'Ortega','role' => 0, 'password' => Hash::make('secret123')
    ));
    }
}