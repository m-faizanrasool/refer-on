<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::create([
            'id' => 1,
            'key' => 'amazon',
            'name' => 'Amazon'
        ]);
        Brand::create([
            'id' => 2,
            'key' => 'ebay',
            'name' => 'Ebay'
        ]);
        Brand::create([
            'id' => 3,
            'key' => 'nike',
            'name' => 'Nike'
        ]);
        Brand::create([
            'id' => 4,
            'key' => 'adidas',
            'name' => 'Adidas'
        ]);
        Brand::create([
            'id' => 5,
            'key' => 'shopee',
            'name' => 'Shopee'
        ]);
        Brand::create([
            'id' => 6,
            'key' => 'nestle',
            'name' => 'Nestle'
        ]);

    }
}
