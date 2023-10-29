<?php

namespace Database\Seeders;

use App\Models\OAuthUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OAuthUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OAuthUser::factory(10)->create();
    }
}
