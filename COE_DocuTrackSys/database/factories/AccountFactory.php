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
            'name' => 'joshua'.$str,
            'email' => 'joshua'.$str.'@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => AccountRole::ADMIN->value
        ];
    }

    public function admin(): Factory {
        return $this->state(function (array $attributes) {
            return [
                'role' => AccountRole::ADMIN->value,
            ];
        });
    }

    public function secretary(): Factory {
        return $this->state(function (array $attributes) {
            return [
                'role' => AccountRole::SECRETARY->value,
            ];
        });
    }

}
