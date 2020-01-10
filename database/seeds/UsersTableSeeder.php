<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Thihazaw',
            'email' => 'thihazaw@user.com',
            'password' => bcrypt('password')
        ]);
        User::create([
            'name' => 'ThantZinSoe',
            'email' => 'thantzinsoe@user.com',
            'password' => bcrypt('password')
        ]);
        User::create([
            'name' => 'MoeSanOo',
            'email' => 'moesanoo@user.com',
            'password' => bcrypt('password')
        ]);
    }
}