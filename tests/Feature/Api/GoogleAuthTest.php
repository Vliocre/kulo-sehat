<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('mobile users can login with a valid google id token', function () {
    config(['services.google.client_id' => 'mobile-client-id.apps.googleusercontent.com']);

    Http::fake([
        'oauth2.googleapis.com/tokeninfo*' => Http::response([
            'aud' => 'mobile-client-id.apps.googleusercontent.com',
            'sub' => 'google-user-id',
            'email' => 'google-user@example.com',
            'email_verified' => 'true',
            'name' => 'Google User',
        ]),
    ]);

    $response = $this->postJson('/api/auth/google', [
        'id_token' => 'valid-google-id-token',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Login Google berhasil')
        ->assertJsonPath('user.email', 'google-user@example.com')
        ->assertJsonStructure(['token']);

    $this->assertDatabaseHas('users', [
        'email' => 'google-user@example.com',
        'google_id' => 'google-user-id',
        'role' => 'pengguna',
    ]);
});

test('mobile google login rejects tokens from another client id', function () {
    config(['services.google.client_id' => 'mobile-client-id.apps.googleusercontent.com']);

    Http::fake([
        'oauth2.googleapis.com/tokeninfo*' => Http::response([
            'aud' => 'other-client-id.apps.googleusercontent.com',
            'sub' => 'google-user-id',
            'email' => 'google-user@example.com',
            'email_verified' => 'true',
        ]),
    ]);

    $response = $this->postJson('/api/auth/google', [
        'id_token' => 'wrong-audience-token',
    ]);

    $response
        ->assertUnauthorized()
        ->assertJsonPath('success', false);
});

test('mobile google login links existing users by email', function () {
    config(['services.google.client_id' => 'mobile-client-id.apps.googleusercontent.com']);

    $user = User::factory()->create([
        'email' => 'existing@example.com',
        'google_id' => null,
        'role' => 'pengguna',
    ]);

    Http::fake([
        'oauth2.googleapis.com/tokeninfo*' => Http::response([
            'aud' => 'mobile-client-id.apps.googleusercontent.com',
            'sub' => 'linked-google-id',
            'email' => 'existing@example.com',
            'email_verified' => 'true',
            'name' => 'Existing User',
        ]),
    ]);

    $response = $this->postJson('/api/auth/google', [
        'id_token' => 'valid-google-id-token',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('user.id', $user->id);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'google_id' => 'linked-google-id',
    ]);
});
