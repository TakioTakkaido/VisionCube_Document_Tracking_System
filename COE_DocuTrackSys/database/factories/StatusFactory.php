<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Status>
 */
class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            ['value' => 'Accepted',             'color' => '#72ba82'],
            ['value' => 'To be Revised',        'color' => '#ed9b42'],
            ['value' => 'In Progress',          'color' => '#d1e66c'],
            ['value' => 'On Hold',              'color' => '#6eb2d4'],
            ['value' => 'To be Followed Up',    'color' => '#76d6cb'],
            ['value' => 'Deferred',             'color' => '#d67699'],
            ['value' => 'Pending',              'color' => '#8476d6'],
            ['value' => 'Voided',               'color' => '#a3a3a3'],
        ];
    }
}
