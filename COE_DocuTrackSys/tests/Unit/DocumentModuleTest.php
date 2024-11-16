<?php

namespace Tests\Unit;

use App\Http\Middleware\NoDirectAccess as NoDirectAccess;
use App\Models\Account;
use Tests\TestCase;
use App\Models\Document;
use App\Models\FileExtension;
use Database\Seeders\DefaultSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

class DocumentModuleTest extends TestCase {

    use RefreshDatabase;

    public function test_view_document_versions(): void {
        // Assume valid document
        $this->seed(DefaultSeeder::class);

        // Assume autheticated login
        $account = Account::create([
            'name' => 'coeDTS_tester',
            'password' => Hash::make('test12345'),
            'email' => 'coedocutracksystest@gmail.com',
            'role' => 'Tester'
        ]);

        // Enter correct input and right 
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'),[
            'email' => 'coedocutracksystest@gmail.com',
            'password' => 'test12345'
        ]);

        $response->assertOk()
             ->assertJsonPath('success', 'Login successful.');

        $account->refresh();

        $this->assertAuthenticatedAs($account);

        // Test document upload
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('document.upload'), [
            'type' => "TestCase",
            'seriesRequired' => false,
            'memoRequired' => false,
            'series_number' => null,
            'memo_number' => null,
            'subject' => "Testing",
            'sender' => json_encode(["Tester1"]),
            'recipient' => json_encode(["Tester2"]),
            'senderArray' => null,
            'recipientArray' => null,
            'document_date' => 'Nov 15, 2024',
            'assignee' => 'Tester',
            'category' => 'Test',
            'status' => 'Testing',
            'files' => [UploadedFile::fake()->create('test.pdf', 100, 'application/pdf')],
        ]);

                
        // Get version
        $response = $this->withoutMiddleware(NoDirectAccess::class)->get(route('document.showDocumentVersions', [
            'id' => 1
        ]));

        $response->assertOk();
    }

    public function test_view_attachments(): void{
        // Assume valid document
        $this->seed(DefaultSeeder::class);

        // Assume autheticated login
        $account = Account::create([
            'name' => 'coeDTS_tester',
            'password' => Hash::make('test12345'),
            'email' => 'coedocutracksystest@gmail.com',
            'role' => 'Tester'
        ]);

        // Enter correct input and right 
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'),[
            'email' => 'coedocutracksystest@gmail.com',
            'password' => 'test12345'
        ]);

        $response->assertOk()
             ->assertJsonPath('success', 'Login successful.');

        $account->refresh();

        $this->assertAuthenticatedAs($account);

        // Test document upload
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('document.upload'), [
            'type' => "TestCase",
            'seriesRequired' => false,
            'memoRequired' => false,
            'series_number' => null,
            'memo_number' => null,
            'subject' => "Testing",
            'sender' => json_encode(["Tester1"]),
            'recipient' => json_encode(["Tester2"]),
            'senderArray' => null,
            'recipientArray' => null,
            'document_date' => 'Nov 15, 2024',
            'assignee' => 'Tester',
            'category' => 'Test',
            'status' => 'Testing',
            'files' => [UploadedFile::fake()->create('test.pdf', 100, 'application/pdf')],
        ]);

                
        // Get version
        $response = $this->withoutMiddleware(NoDirectAccess::class)->get(route('document.showAttachments', [
            'id' => 1
        ]));

        $response->assertOk();
    }

    public function test_document_upload(): void{// Assume valid document
        $this->seed(DefaultSeeder::class);

        // Assume autheticated login
        $account = Account::create([
            'name' => 'coeDTS_tester',
            'password' => Hash::make('test12345'),
            'email' => 'coedocutracksystest@gmail.com',
            'role' => 'Tester'
        ]);

        // Enter correct input and right 
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('account.login'),[
            'email' => 'coedocutracksystest@gmail.com',
            'password' => 'test12345'
        ]);

        $response->assertOk()
             ->assertJsonPath('success', 'Login successful.');

        $account->refresh();

        $this->assertAuthenticatedAs($account);

        // Test invalid memoranda upload
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('document.upload'), [
            'type' => "TestCase",
            'seriesRequired' => false,
            'memoRequired' => false,
            'series_number' => null,
            'memo_number' => null,
            'subject' => "Testing",
            'sender' => json_encode(["Tester1"]),
            'recipient' => json_encode(["Tester2"]),
            'senderArray' => null,
            'recipientArray' => null,
            'document_date' => 'Nov 15, 2024',
            'assignee' => 'Tester',
            'category' => 'Test',
            'status' => 'Testing',
            'files' => [UploadedFile::fake()->create('test.pdf', 100, 'application/pdf')],
        ]);

        $response->assertUnprocessable()
                ->assertJsonPath('errors.type', '')
                ->assertJsonPath('', '');
    }
}
