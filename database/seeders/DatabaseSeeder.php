<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('personal_access_tokens')->insert([
            'name' => Str::random(10),
            'token' => Hash::make(Str::random(10)),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \App\Models\Position::factory(10)->create();
        \App\Models\User::factory(10)->create();
    }
}
