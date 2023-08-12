<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = [
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'role' => 0,
                'password' => bcrypt('12345678'),
            ],

            [
                'name' => 'Supplier',
                'email' => 'supplier@gmail.com',
                'role' => 1,
                'password' => bcrypt('12345678'),
            ],

            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'role' => 2,
                'password' => bcrypt('12345678'),
            ]

        ];

        foreach ($users as $key => $user) 
        {
            User::create($user);
        }
    }
}
