<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SysInfo>
 */
class SysInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'COE Document Tracking System',
            'logo' => 'logo.png',
            'favicon' => 'icon.png',
            'about' => 'about',
            'mission' => 'mission',
            'vision' => 'vision'
        ];
    }
}
