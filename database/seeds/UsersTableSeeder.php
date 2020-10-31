<?php

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
        DB::table('users')->insert([
            'username' => 'edenramoneda',
            'email' => 'edenlee143@gmail.com',
            'user_img' => 'user.png',
            'password' => Hash::make('12345678'),
        ]);

        DB::table('users')->insert([
            'username' => 'lei',
            'email' => 'lecias.ariel@gmail.com',
            'user_img' => 'user.png',
            'password' => Hash::make('12345678'),
        ]);
    }
}
