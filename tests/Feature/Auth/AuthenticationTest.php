<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'role' => 'pengguna',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('admins can authenticate without selecting a role', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});

test('non admin users can not authenticate without selecting a role', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
    ]);

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('role');
    $this->assertGuest();
});

test('users can not authenticate with a mismatched role selection', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
    ]);

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'password',
        'role' => 'dokter',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('role');
    $this->assertGuest();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
        'role' => 'pengguna',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
