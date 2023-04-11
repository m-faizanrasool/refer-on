<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (env('APP_ENV') !== 'production') {
            $this->call(CountrySeeder::class);
            $this->call(UserSeeder::class);
            $this->call(BrandSeeder::class);
        }
    }
}
