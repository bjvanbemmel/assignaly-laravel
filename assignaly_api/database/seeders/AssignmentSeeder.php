<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'owner_id' => 1,
            'title' => $faker->sentence(),
            'description' => $faker->paragraph(),
            'due_at' => $faker->dateTimeThisYear(),
            'finished_at' => null,
            'review' => $faker->numberBetween(1, 10),
            'feedback' => $faker->realText,
        ]);

        DB::table('assignments')->insert([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'owner_id' => 1,
            'title' => $faker->sentence(),
            'description' => $faker->paragraph(),
            'due_at' => $faker->dateTimeThisYear(),
            'finished_at' => null,
            'review' => $faker->numberBetween(1, 10),
            'feedback' => $faker->realText,
        ]);
    }
}
