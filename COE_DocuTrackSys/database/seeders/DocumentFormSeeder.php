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
        $participants = Participant::factory()->definition();

        foreach($participants as $participant){
            Participant::create($participant);
        }
        
        // Make participant groups
        $groups = ParticipantGroup::factory()->definition();

        foreach ($groups as $group) {
            ParticipantGroup::create($group);
        }

        // Make types
        $types = Type::factory()->definition();

        foreach($types as $type){
            Type::create($type);
        }

        // Make status
        $statuses = Status::factory()->definition();

        foreach ($statuses as $status){
            Status::create($status);
        }

        // Make file extensions
        // Seed file extensions
        $fileExtensions = FileExtension::factory()->definition(); // Get the predefined array from the factory

        foreach ($fileExtensions as $extension) {
            FileExtension::create($extension); // Create each extension
        }
        
    }
}
