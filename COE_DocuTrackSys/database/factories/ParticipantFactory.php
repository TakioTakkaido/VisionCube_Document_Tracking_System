<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            ['value' => 'WMSU President'],
            ['value' => 'VPAA'],
            ['value' => 'Dean - College of Engr.'],
            ['value' => 'Dept. Head - ABE'],
            ['value' => 'Dept. Head - CE'],
            ['value' => 'Dept. Head - CPE'],
            ['value' => 'Dept. Head - ECE'],
            ['value' => 'Dept. Head - ENE'],
            ['value' => 'Dept. Head - EE'],
            ['value' => 'Dept. Head - GE'],
            ['value' => 'Dept. Head - IE'],
            ['value' => 'Dept. Head - ME'],
            ['value' => 'Dept. Head - SE'],
        ];
    }
}
