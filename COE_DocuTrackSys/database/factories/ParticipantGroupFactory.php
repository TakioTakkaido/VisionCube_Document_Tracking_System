<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=ParticipantGroup>
 */
class ParticipantGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            ['value' => 'COE Dept. Heads'],
            ['value' => 'ABE Faculty'],
            ['value' => 'CE Faculty'],
            ['value' => 'CPE Faculty'],
            ['value' => 'ECE Faculty'],
            ['value' => 'ENE Faculty'],
            ['value' => 'EE Faculty'],
            ['value' => 'GE Faculty'],
            ['value' => 'IE Faculty'],
            ['value' => 'ME Faculty'],
            ['value' => 'SE Faculty']
        ];
    }
}
