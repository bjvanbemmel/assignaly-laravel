<?php

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        DB::table('users')->insert([
            'name' => $faker->name(),
            'email' => $faker->email(),
            'password' => $faker->password(),
            'institute_id' => Institute::first()->id,
            'role_id' => Role::first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        //
    }
}
