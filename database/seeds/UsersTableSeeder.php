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
            'name' => 'Microstack',
            'email' => 'microstack@admin.com',
            'password' => bcrypt('microstack'),
            'role' => 1
        ]);
        User::create([
            'name' => 'Mandalay',
            'email' => 'mandalay@user.com',
            'password' => bcrypt('537656')
        ]);
        User::create([
            'name' => 'Yangon',
            'email' => 'yangon@user.com',
            'password' => bcrypt('722722')
        ]);
    }
}