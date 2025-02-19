<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Client;
use App\Models\Contributor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * Semillas para contribuidor y usuarios por cada contribuidor
         */       
        Contributor::factory()->count(10)->has(User::factory(10))->has(Client::factory(10))->create();
        // Contributor::factory()->count(10)->create();
    }
}
