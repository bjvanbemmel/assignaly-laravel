<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        DB::table('assignments')->insert([
            'owner_id' => 1,
            'title' => $faker->sentence(),
            'description' => $faker->paragraph(),
            'due_at' => $faker->dateTimeThisYear(),
            'finished_at' => null,
            'numeric_review' => $faker->numberBetween(1, 10),
            'alphabetic_review' => null,
            'feedback' => $faker->realText,
        ]);

        DB::table('assignments')->insert([
            'owner_id' => 1,
            'title' => $faker->sentence(),
            'description' => $faker->paragraph(),
            'due_at' => $faker->dateTimeThisYear(),
            'finished_at' => null,
            'numeric_review' => $faker->numberBetween(1, 10),
            'alphabetic_review' => null,
            'feedback' => $faker->realText,
        ]);
    }
}
