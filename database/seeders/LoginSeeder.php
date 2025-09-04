<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new \App\Models\User;
        $user->name = 'Nisa';
        $user->email = 'nisa@gmail.com';
        $user->serial_number = 'IUR-123456';
        $user->password = Hash::make('123456');
        $user->save();
    }
}
