<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Get all brand IDs from the 'brands' table
        $brandIds = DB::table('brands')->pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            DB::table('products')->insert([
                'name' => $faker->name,
                'slug' => $faker->slug,
                'description' => $faker->paragraph,
                'image' => $faker->image('public/storage/uploads/brands', 640, 480, 'business', false),
                'price' => $faker->numberBetween(1000, 10000),
                'quantity' => $faker->numberBetween(1, 100),
                'brand_id' => $faker->randomElement($brandIds),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
