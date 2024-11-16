<?php

namespace Tests\Unit;

use App\Http\Middleware\NoDirectAccess;
use App\Models\Account;
use App\Models\Participant;
use App\Models\ParticipantGroup;
use App\Models\Status;
use App\Models\Type;
use Database\Seeders\DefaultSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SystemSettingsModuleTest extends TestCase {
    use RefreshDatabase;

    private function login_user(){
        $this->seed(DefaultSeeder::class);
        // Assume the user is logged in
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
    }

    public function test_update_maintenance(): void {
        // Assume the user is logged in
        $this->login_user();


        // Update the maintenance status
        // True
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('settings.update'),[
            'maintenance' => true
        ]);

        $response->assertOk()
                ->assertJsonPath("success", "Settings saved");

        // False
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('settings.update'),[
            'maintenance' => false
        ]);

        $response->assertOk()
                ->assertJsonPath("success", "Settings saved");
    }

    public function test_add_participant(): void {
        // Assume the user is logged in
        $this->login_user();
        
        // Add a new participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('participant.update'),[
            'value' => "Test",
            'id' => null
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Participant updated successfully.');
        
        $this->assertDatabaseHas('participants', [
            'value' => "Test"
        ]);
    }

    public function test_update_participant(): void {
        // Assume the user is logged in
        $this->login_user();

        // Add a participant
        Participant::create([
            "value" => 'Test'
        ]);

        $test = Participant::where("value", "Test")->first();
        // Update a participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('participant.update'),[
            'value' => "Test1",
            "id" => $test->id
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Participant updated successfully.');
        
        $this->assertDatabaseHas('participants', [
            'value' => "Test1",
            "id" => $test->id
        ]);
    }

    public function test_delete_participant(): void {
        // Assume the user is logged in
        $this->login_user();

        // Add a participant
        Participant::create([
            "value" => 'Test'
        ]);

        $test = Participant::where("value", "Test")->first();

        // Update a participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('participant.delete'),[
            "id" => $test->id
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Participant deleted successfully');
        
        $this->assertDatabaseMissing('participants', [
            "id" => $test->id
        ]);
    }

    public function test_add_group(): void {
        // Assume the user is logged in
        $this->login_user();
        
        // Add a new participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('participantGroup.update'),[
            'value' => "Test",
            'id' => null
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Participant updated successfully.');
        
        $this->assertDatabaseHas('participant_groups', [
            'value' => "Test"
        ]);
    }

    public function test_update_group(): void {
        // Assume the user is logged in
        $this->login_user();
        
        // Add a participant
        ParticipantGroup::create([
            "value" => 'Test'
        ]);

        $test = ParticipantGroup::where("value", "Test")->first();

        // Add a new participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('participantGroup.update'),[
            'value' => "Test1",
            'id' => $test->id
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Participant updated successfully.');
        
        $this->assertDatabaseHas('participant_groups', [
            'value' => "Test1",
            'id' => $test->id
        ]);
    }

    public function test_update_group_members(): void {
        // Assume the user is logged in
        $this->login_user();
        
        // Add a participant
        ParticipantGroup::create([
            "value" => 'Test'
        ]);

        ParticipantGroup::create([
            "value" => 'Test2'
        ]);

        Participant::create([
            "value" => 'Test3'
        ]);

        $test = ParticipantGroup::where("value", "Test")->first();
        $testGroup = ParticipantGroup::where("value", "Test2")->first();
        $testParticipant = Participant::where("value", "Test3")->first();

        // Add a new participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('participantGroup.updateParticipantGroupMembers'),[
            'id' => $test->id,
            'participantGroupsIDs' => [$testGroup->id],
            'participantIDs' => [$testParticipant->id]
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Groups under the participant group updated successfully');
        
        // Assert that the participant was attached to the group
        $this->assertDatabaseHas('participant_group_participant', [
            'participant_group_id' => $test->id,
            'participant_id' => $testParticipant->id
        ]);

        // Assert that the group was attached to the group
        $this->assertDatabaseHas('participant_group_participant_group', [
            'parent_participant_group_id' => $test->id,
            'participant_group_id' => $testGroup->id
        ]);
    }

    public function test_delete_group(): void {
        // Assume the user is logged in
        $this->login_user();
        
        // Add a participant
        ParticipantGroup::create([
            "value" => 'Test'
        ]);

        $test = ParticipantGroup::where("value", "Test")->first();

        // Add a new participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('participantGroup.delete'),[
            'id' => $test->id
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Participant group deleted successfully');
        
        $this->assertDatabaseMissing('participant_groups', [
            'id' => $test->id
        ]);
    }

    public function test_add_type(): void {
        // Assume the user is logged in
        $this->login_user();
        
        // Add a new participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('type.update'),[
            'value' => "Test",
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Type edited successfully.');
        
        $this->assertDatabaseHas('types', [
            'value' => "Test"
        ]);
    }

    public function test_update_type(): void {
        // Assume the user is logged in
        $this->login_user();

        // Add a participant
        Type::create([
            "value" => 'Test'
        ]);

        $test = Type::where("value", "Test")->first();

        // Update a participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('type.update'),[
            'value' => "Test1",
            "id" => $test->id
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Type edited successfully.');
        
        $this->assertDatabaseHas('types', [
            'value' => "Test1",
            "id" => $test->id
        ]);
    }

    public function test_delete_type(): void {
        // Assume the user is logged in
        $this->login_user();
        
        // Add a participant
        Type::create([
            "value" => 'Test'
        ]);

        $test = Type::where("value", "Test")->first();

        // Add a new participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('type.delete'),[
            'id' => $test->id
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Type deleted successfully.');
        
        $this->assertDatabaseMissing('types', [
            'id' => $test->id
        ]);
    }

    public function test_add_status(): void {
        // Assume the user is logged in
        $this->login_user();
        
        // Add a new participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('status.update'),[
            'value' => "Test",
            'color' => '#ffffff'
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Status edited successfully.');
        
        $this->assertDatabaseHas('statuses', [
            'value' => "Test",
            'color' => '#ffffff'
        ]);
    }

    public function test_update_status(): void {
        // Assume the user is logged in
        $this->login_user();

        // Add a participant
        Status::create([
            "value" => 'Test',
            'color' => '#ffffff'
        ]);

        $test = Status::where("value", "Test")->first();

        // Update a participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('status.update'),[
            'value' => "Test1",
            'color' => '#000000',
            "id" => $test->id
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Status edited successfully.');
        
        $this->assertDatabaseHas('statuses', [
            'value' => "Test1",
            'color' => '#000000',
            "id" => $test->id
        ]);
    }

    public function test_delete_status(): void {
        // Assume the user is logged in
        $this->login_user();
        
        // Add a participant
        Status::create([
            "value" => 'Test',
            'color' => '#ffffff'
        ]);

        $test = Status::where("value", "Test")->first();

        // Add a new participant
        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('status.delete'),[
            'id' => $test->id
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'Status deleted successfully.');
        
        $this->assertDatabaseMissing('statuses', [
            'id' => $test->id
        ]);
    }

    public function test_update_file_extensions():void {
        // Assume user is logged in
        $this->login_user();

        $response = $this->withoutMiddleware(NoDirectAccess::class)->postJson(route('fileExtension.update'),[
            'extensions' => [
                false,
                false,
                false,
                false,
                false,
            ]
        ]);

        $response->assertOk()
                ->assertJsonPath('success', 'File extensions edited succesfully.');
        
        $this->assertDatabaseHas('file_extensions', [
            'id' => 1, 
            'checked' => false
        ]);

        $this->assertDatabaseHas('file_extensions', [
            'id' => 2, 
            'checked' => false,
        ]);

        $this->assertDatabaseHas('file_extensions', [
            'id' => 3, 
            'checked' => false
        ]);

        $this->assertDatabaseHas('file_extensions', [
            'id' => 4, 
            'checked' => false
        ]);

        $this->assertDatabaseHas('file_extensions', [
            'id' => 5, 
            'checked' => false
        ]);
    }
}
