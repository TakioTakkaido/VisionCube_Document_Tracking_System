<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel=FileExtension>
 */
class FileExtensionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            ['value' => 'pdf', 'checked' => true],
            ['value' => 'doc', 'checked' => true],
            ['value' => 'docx', 'checked' => true],
            ['value' => 'xls', 'checked' => true],
            ['value' => 'xlsx', 'checked' => true]
        ];
    }
}
