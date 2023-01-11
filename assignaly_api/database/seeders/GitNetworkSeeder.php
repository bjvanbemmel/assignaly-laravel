<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GitNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('git_networks')->insert([
            'name' => 'Github',
            'public_url' => 'https://github.com/',
            'api_url' => 'https://api.github.com/',
        ]);
    }
}
