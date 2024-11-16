<?php

namespace Tests\Unit;

use Database\Factories\AccountFactory;
use Tests\TestCase;
use App\Models\Account as Account;
use App\Http\Middleware\NoDirectAccess as NoDirectAccess;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginFormTest extends TestCase {
    use RefreshDatabase;
    public function test_email_and_password_required(): void {
        // Create a fake account
        $account = Account::create([
            'name' => 'coeDTS_tester',
            'password' => Hash::make('test12345'),
            'email' => 'coedocutracksystest@gmail.com',
            'role' => 'Tester'
        ]);

        // Enter empty form data
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'), [
            'email' => '',
            'password' => ''
        ]);

        // Must give off errors that the form data requries the email and the password
        $response->assertUnprocessable()
                ->assertJsonPath('errors.email.0', 'The email field is required.')
                ->assertJsonPath('errors.password.0', 'The password field is required.');
        
        // Enter form with password but not email
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'), [
            'email' => '',
            'password' => 'test12345'
        ]);

        // Email required
        $response->assertUnprocessable()
                ->assertJsonPath('errors.email.0', 'The email field is required.');


        // Password required
        // Enter form with password but not email
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'), [
            'email' => 'coedocutracksystest@gmail.com',
            'password' => ''
        ]);

        // Email required
        $response->assertUnprocessable()
                ->assertJsonPath('errors.password.0', 'The password field is required.');

        // Response is ok
        // Correct form data
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'), [
            'email' => 'coedocutracksystest@gmail.com',
            'password' => 'test12345'
        ]);

        $response->assertOk();
    }

    public function test_valid_email_input(): void {
        // Create a fake account
        $account = Account::create([
            'name' => 'coeDTS_tester',
            'password' => Hash::make('test12345'),
            'email' => 'coedocutracksystest@gmail.com',
            'role' => 'Tester'
        ]);

        // Have the user enter invalid email input
        // Invalid email
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'),[
            'email' => 'coedocutrack',
            'password' => 'test12345'
        ]);

        $response->assertUnprocessable()
                ->assertJsonPath('errors.email.0', 'Please enter valid e-mail.');

        // 255 characters long email
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'),[
            'email' => 'coedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrackcoedocutrack@gmail.com',
            'password' => 'test12345'
        ]);

        $response->assertUnprocessable()
                ->assertJsonPath('errors.email.0', 'Email entered must be less than 255 characters.');

        // Email not registered by the admin
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'),[
            'email' => 'documenttrackingsystem@gmail.com',
            'password' => 'test12345'
        ]);

        $response->assertUnprocessable()
                ->assertJsonPath('errors.email.0', 'Email entered is not registered to the system.');

        // Response is ok
        // Correct form data
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'), [
            'email' => 'coedocutracksystest@gmail.com',
            'password' => 'test12345'
        ]);

        $response->assertOk();
    }

    public function test_password_must_match_password_input(): void {
        $account = Account::create([
            'name' => 'coeDTS_tester',
            'password' => Hash::make('test12345'),
            'email' => 'coedocutracksystest@gmail.com',
            'role' => 'Tester'
        ]);

        // Have the user enter the emalil but wrong password
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'),[
            'email' => 'coedocutracksystest@gmail.com',
            'password' => 'test123',
        ]);

        $response->assertUnprocessable()
                ->assertJsonPath('errors.password', 'Password does not match or exist for the account.');
    }

    public function test_save_account_session(): void {
        $account = Account::create([
            'name' => 'coeDTS_tester',
            'password' => Hash::make('test12345'),
            'email' => 'coedocutracksystest@gmail.com',
            'role' => 'Tester'
        ]);

        // Enter correct input and right 
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'),[
            'email' => 'coedocutracksystest@gmail.com',
            'password' => 'test12345',
            'remember' => true
        ]);

        $response->assertOk()
             ->assertJsonPath('success', 'Login successful.');

        $account->refresh();

        $this->assertAuthenticatedAs($account);
        $this->assertDatabaseHas('accounts', [
            'email' => 'coedocutracksystest@gmail.com',
            'remember_token' => $account->remember_token,
        ]);
    }
}
