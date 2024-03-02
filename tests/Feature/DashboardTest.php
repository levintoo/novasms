<?php

use App\Models\User;

test('dashboard is displayed for logged-in users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
});

test('dashboard access requires login', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});
