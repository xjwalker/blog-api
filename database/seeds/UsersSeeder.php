<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now();

        \Illuminate\Support\Facades\DB::table('users')->insert([
            [
                'name' => 'kevinwalker',
                'email' => 'kevin.walker@gmail.com',
                'password' => bcrypt('PWD4kw!!'),
                'verified' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'johnwalker',
                'email' => 'john.walker@gmail.com',
                'password' => bcrypt('PWD4jw!!'),
                'verified' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'emiliawalker',
                'email' => 'emilia.walker@gmail.com',
                'password' => bcrypt('PWD4ew!!'),
                'verified' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }
}
