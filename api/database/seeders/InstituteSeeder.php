<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        DB::table('institutes')->insert([
            'name' => $faker->company(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
