<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('allows users to log in', function () {
    $user = User::factory()->create();
    
    // Generate JWT token
    $token = JWTAuth::fromUser($user);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200);
    $response->assertSeeText('access_token');
});


test('Auth User response', function () {
    $user = User::factory()->create();
    
    // Generate JWT token
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)->getJson('/api/user');
    $response->assertStatus(200);
});


test('logout route response', function () {
    $user = User::factory()->create();
    
    // Generate JWT token
    $token = JWTAuth::fromUser($user);

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/logout');
    $response->assertStatus(200);
});
