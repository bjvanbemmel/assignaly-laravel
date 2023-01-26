<?php

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();

        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@test.nl',
            'password' => Hash::make('P4$$W0Rd'),
            'institute_id' => Institute::first()->id,
            'role_id' => Role::first()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@test.nl',
            'password' => Hash::make('P4$$W0Rd'),
            'institute_id' => Institute::first()->id,
            'role_id' => Role::find(3)->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Test Teacher',
            'email' => 'teacher@test.nl',
            'password' => Hash::make('P4$$W0Rd'),
            'institute_id' => Institute::first()->id,
            'role_id' => Role::find(3)->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
