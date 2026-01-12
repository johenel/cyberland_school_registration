<?php
use App\Models\Application;

it('can submit application with valid params', function () {
    $response = $this->postJson('/api/v1/applications', [
        'guardian_first_name' => 'johenel',
        'guardian_last_name' => 'labayan',
        'guardian_email' => 'johenel.guardian@gmail.com',
        'guardian_contact_number' => '0912345678',
        'student_first_name' => 'leighton lyonesse',
        'student_last_name' => 'labayan',
        'student_birth_date' => '2016-12-31',
        'guardian_relationship' => 'father'
    ]);

    $response->assertOk();
});

it('cannot access application list if user is not admin', function () {
    $user = \App\Models\User::query()->where('email', 'johenel.guardian@gmail.com')->first();
    $this->assertNotEmpty($user);
    $this->actingAs($user);
    // Test user api access
    $response = $this->getJson('/api/v1/applications');
    // Expect response to be forbidden
    $response->assertStatus(403);
});

it('cannot process application if user is not admin', function () {
    $user = \App\Models\User::query()->where('email', 'johenel.guardian@gmail.com')->first();
    $this->assertNotEmpty($user);
    $this->actingAs($user);
    // Get a pending application
    $application = Application::query()->where('status', Application::STATUS_PENDING)->first();
    $this->assertNotEmpty($application);
    // Test user api access
    $response = $this->postJson("/api/v1/applications/{$application->id}/process");
    // Expect response to be forbidden
    $response->assertStatus(403);
});

it('cannot hold application if user is not admin', function () {
    $user = \App\Models\User::query()->where('email', 'johenel.guardian@gmail.com')->first();
    $this->assertNotEmpty($user);
    $this->actingAs($user);
    // Get a pending application
    $application = Application::query()->where('status', Application::STATUS_PENDING)->first();
    $this->assertNotEmpty($application);
    // Test user api access
    $response = $this->postJson("/api/v1/applications/{$application->id}/on-hold", [
        'remarks' => 'Sample hold reason'
    ]);
    // Expect response to be forbidden
    $response->assertStatus(403);
});

it('cannot reject application if user is not admin', function () {
    $user = \App\Models\User::query()->where('email', 'johenel.guardian@gmail.com')->first();
    $this->assertNotEmpty($user);
    $this->actingAs($user);
    // Get a pending application
    $application = Application::query()->where('status', Application::STATUS_PENDING)->first();
    $this->assertNotEmpty($application);
    // Test user api access
    $response = $this->postJson("/api/v1/applications/{$application->id}/reject", [
        'remarks' => 'Sample reject reason'
    ]);
    // Expect response to be forbidden
    $response->assertStatus(403);
});

it('cannot accept application if user is not admin', function () {
    $user = \App\Models\User::query()->where('email', 'johenel.guardian@gmail.com')->first();
    $this->assertNotEmpty($user);
    $this->actingAs($user);
    // Get a pending application
    $application = Application::query()->where('status', Application::STATUS_PENDING)->first();
    $this->assertNotEmpty($application);
    // Test user api access
    $response = $this->postJson("/api/v1/applications/{$application->id}/accept");
    // Expect response to be forbidden
    $response->assertStatus(403);
});

it('can access application list if user is admin', function () {
    $user = \App\Models\User::query()->where('email', 'johenel.guardian@gmail.com')->first();
    $this->assertNotEmpty($user);
    // Assign admin role to the user
    $adminRole = \App\Models\Role::query()->where('name', 'admin')->first();
    $this->assertNotEmpty($adminRole);
    $user->role()->associate($adminRole);
    $user->save();
    $this->actingAs($user);
    // Test user api access
    $response = $this->getJson('/api/v1/applications');
    $response->assertOk();
});

it('can process application if user is admin', function () {
    $user = \App\Models\User::query()->where('email', 'johenel.guardian@gmail.com')->first();
    $this->assertNotEmpty($user);
    $this->actingAs($user);
    // Get a pending application
    $application = Application::query()->where('status', Application::STATUS_PENDING)->first();
    $this->assertNotEmpty($application);
    // Test user api access
    $response = $this->postJson("/api/v1/applications/{$application->id}/process");
    // Expect response to be forbidden
    $response->assertStatus(200);
});

it('can hold application if user is admin', function () {
    $user = \App\Models\User::query()->where('email', 'johenel.guardian@gmail.com')->first();
    $this->assertNotEmpty($user);
    $this->actingAs($user);
    // Get a pending application
    $application = Application::query()->where('status', Application::STATUS_PROCCESSING)->first();
    $this->assertNotEmpty($application);
    // Test user api access
    $response = $this->postJson("/api/v1/applications/{$application->id}/on-hold", [
        'remarks' => 'Sample hold reason'
    ]);
    // Expect response to be forbidden
    $response->assertStatus(200);
});

it('can reject application if user is admin', function () {
    $user = \App\Models\User::query()->where('email', 'johenel.guardian@gmail.com')->first();
    $this->assertNotEmpty($user);
    $this->actingAs($user);
    // Get a pending application
    $application = Application::query()->where('status', Application::STATUS_ON_HOLD)->first();
    $this->assertNotEmpty($application);
    // Test user api access
    $response = $this->postJson("/api/v1/applications/{$application->id}/reject", [
        'remarks' => 'Sample reject reason'
    ]);
    // Expect response to be forbidden
    $response->assertStatus(200);
});

it('can accept application if user is admin', function () {
    $user = \App\Models\User::query()->where('email', 'johenel.guardian@gmail.com')->first();
    $this->assertNotEmpty($user);
    $this->actingAs($user);
    // Get a pending application
    $application = Application::query()->where('status', Application::STATUS_REJECTED)->first();
    $this->assertNotEmpty($application);
    // Test user api access
    $response = $this->postJson("/api/v1/applications/{$application->id}/accept");
    // Expect response to be forbidden
    $response->assertStatus(200);
});

