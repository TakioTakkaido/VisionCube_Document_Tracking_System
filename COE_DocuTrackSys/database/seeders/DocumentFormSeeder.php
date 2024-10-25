<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FileExtension;
use App\Models\Participant;
use App\Models\ParticipantGroup;
use App\Models\Status;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Make participants
        for ($i=0; $i < 10; $i++) { 
            Participant::factory()->state([
                'value' => 'Participant'.$i
            ])->create();
        }
        
        // Make participant groups
        for ($i=0; $i < 10; $i++) { 
            ParticipantGroup::factory()->state([
                'value' => 'Group'.$i
            ])->create();
        }

        // Make types
        for ($i=0; $i < 10; $i++) { 
            Type::factory()->state([
                'value' => 'Type'.$i
            ])->create();
        }

        // Make status
        for ($i=0; $i < 10; $i++) { 
            Status::factory()->state([
                'value' => 'Status'.$i
            ])->create();
        }

        // Make categories
        for ($i=0; $i < 10; $i++) { 
            Category::factory()->state([
                'value' => 'Category'.$i
            ])->create();
        }

        // Make file extensions
        // Seed file extensions
        $fileExtensions = FileExtension::factory()->definition(); // Get the predefined array from the factory

        foreach ($fileExtensions as $extension) {
            FileExtension::create($extension); // Create each extension
        }
        
    }
}
