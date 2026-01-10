<?php


it('can register with valid credentials', function ()  {
    $response = $this->postJson('/api/register', [
        'email' => 'johenelpl@gmail.com',
        'name' => 'Johenel Labayan',
        'password' => 'pass123!',
        'password_confirmation' => 'pass123!'
    ]);

    $response->assertOk();

    expect(\App\Models\User::count())->toBe(1);
});

it('can login with valid credentials', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'johenelpl@gmail.com',
        'password' => 'pass123!'
    ]);

    $response->assertOk();
});
