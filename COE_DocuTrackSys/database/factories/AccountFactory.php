<?php

namespace Database\Factories;

use App\AccountRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $str = random_int(100,999);
        return [
            'password' => Hash::make('12345678'),
        ];
    }

    public function admin(): Factory {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'joshua1008',
                'email' => 'joshua1008@gmail.com',
                'role' => 'Admin',
                'canUpload' => true,
                'canEdit' => true,
                'canMove' => true,
                'canArchive' => true,
                'canDownload' => true,
                'canPrint' => true,
            ];
        });
    }

    public function secretary(): Factory {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'joshua8001',
                'email' => 'joshua8001@gmail.com',
                'role' => 'Secretary',
            ];
        });
    }

}
