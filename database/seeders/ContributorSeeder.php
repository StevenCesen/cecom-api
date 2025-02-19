<?php

namespace Database\Seeders;

use App\Models\Contributor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContributorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contributor::factory()->count(10)->create();
    }
}
