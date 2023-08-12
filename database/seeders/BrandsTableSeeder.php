<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('brands')->insert([
                'name' => $faker->company,
                'slug' => $faker->slug,
                'description' => $faker->paragraph,
                'image' => $faker->image('public/storage/uploads/brands', 640, 480, 'business', false),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
