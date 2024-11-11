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
                'name' => 'coeDTS_admin',
                'email' => 'coedocutracksys@gmail.com',
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
                'name' => 'coeDTS_secretary',
                'email' => 'calulutjoshuamiguel@gmail.com',
                'role' => 'Secretary',
                'canUpload' => true,
                'canEdit' => true,
                'canMove' => true,
                'canArchive' => true,
                'canDownload' => true,
                'canPrint' => true,
            ];
        });
    }

    public function assistant(): Factory {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'coeDTS_assistant',
                'email' => 'eh202201752@wmsu.edu.ph',
                'role' => 'Assistant',
                'canUpload' => true,
                'canEdit' => true,
                'canMove' => true,
                'canArchive' => true,
                'canDownload' => true,
                'canPrint' => true,
            ];
        });
    }

    public function clerk(): Factory {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'coeDTS_clerk',
                'email' => 'papajoshua100804@gmail.com',
                'role' => 'Clerk',
                'canUpload' => true,
                'canEdit' => true,
                'canMove' => true,
                'canArchive' => true,
                'canDownload' => true,
                'canPrint' => true,
            ];
        });
    }
}
