<?php

namespace Tests\Unit;

use Database\Factories\AccountFactory;
use Tests\TestCase;
use App\Models\Account as Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginFormTest extends TestCase {
    use RefreshDatabase;
    // Assert that the 
    public function test_email_and_password_required(): void {
        // Sending invalid data to trigger validation errors
        $response = $this->withoutMiddleware()->post(route('account.login'));

        // Assert that both 'email' and 'password' fields have validation errors
        $response->assertInvalid(['email']);
        $response->assertInvalid(['password']);
    }

    public function test_valid_email_input(): void {
        $response = $this->withoutMiddleware()->post(route('account.login'),[
            'email' => 'coedocutrack'
        ]);

        $response->assertInvalid(['email' => 'Please enter valid e-mail.']);
    }

    public function test_password_must_match_password_input(): void {
        $account = Account::create([
            'name' => 'coeDTS_tester',
            'password' => Hash::make('test12345'),
            'email' => 'coedocutracksystest@gmail.com',
            'role' => 'Tester'
        ]);

        $response = $this->withoutMiddleware()->post(route('account.login'),[
            'email' => 'coedocutracksystest@gmail.com',
            'password' => 'test123',
        ]);

        $response->assertInvalid(['password']);
    }

    public function test_save_account_session(): void {
        $account = Account::create([
            'name' => 'coeDTS_tester',
            'password' => Hash::make('test12345'),
            'email' => 'coedocutracksystest@gmail.com',
            'role' => 'Tester'
        ]);

        $response = $this->withoutMiddleware()->post(route('account.login'),[
            'email' => 'coedocutracksystest@gmail.com',
            'password' => 'test12345',
            'remember' => true
        ]);

        $response->assertOk();

        $account->refresh();

        $account->assertNotNull($account->remember_token);
    }
}
