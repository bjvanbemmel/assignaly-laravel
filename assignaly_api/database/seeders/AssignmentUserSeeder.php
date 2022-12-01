<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignmentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('assignment_users')->insert([
            'assignment_id' => 1,
            'user_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::all()->except(Assignment::find(2)->owner->id)->each(function (User $user) {
            DB::table('assignment_users')->insert([
                'assignment_id' => 2,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
