<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $users = [
            [
                'id' => Str::uuid(),
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('adminpassword'),
                'role' => 'admin',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'test',
                'email' => 'test@example.com',
                'password' => Hash::make('12345test'),
                'role' => 'user',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'test2',
                'email' => 'test2@example.com',
                'password' => Hash::make('12345test'),
                'role' => 'user',
            ],
            [
                'id' => Str::uuid(),
                'name' => 'test3',
                'email' => 'test3@example.com',
                'password' => Hash::make('12345test'),
                'role' => 'user',
            ]
        ];

        DB::table('users')->insert($users);
    }
}