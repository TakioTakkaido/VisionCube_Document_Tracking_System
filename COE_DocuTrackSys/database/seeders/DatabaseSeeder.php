<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FileExtension;
use App\Models\Participant;
use App\Models\Status;
use App\Models\Type;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {
        FileExtension::factory()->count(5)->create();
        Participant::factory()->count(10)->create();
        Type::factory()->count(10)->create();
        Category::factory()->count(10)->create();
        Status::factory()->count(10)->create();
    }
}
