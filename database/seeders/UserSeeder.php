<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "id" => 1,
            "is_admin" => true,
            "country_id" => 148,
            "username" => 'Demo',
            "email" => 'demo@referon.com',
            "phone" => '+92301112223',
            "password" => 'demo',
        ]);

        User::create([
            "id" => 2,
            "is_admin" => false,
            "country_id" => 170,
            "username" => 'client',
            "email" => 'client@referon.com',
            "phone" => '+923001234567',
            "password" => 'client',
        ]);
    }
}
