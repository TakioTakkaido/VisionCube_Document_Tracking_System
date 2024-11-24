<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Drive>
 */
class DriveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array{
        return [
            'email' => 'coedocutracksys@gmail.com',
            'verified_at' => now(),
            'disabled' => false,
            'canDocument' => true,
            'canReport' => true,
            'refresh_token' => \config('services.google.refresh_token')
        ];
    }
}
