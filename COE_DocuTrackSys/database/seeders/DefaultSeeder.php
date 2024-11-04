<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\FileExtension;
use App\Models\Participant;
use App\Models\ParticipantGroup;
use App\Models\Settings;
use App\Models\Status;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Make accounts
        Account::factory()->admin()->create();
        Account::factory()->secretary()->create();

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

        // Create settings
        Settings::factory()->create();
    }
}
