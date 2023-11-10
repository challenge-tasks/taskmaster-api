<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_can_register(): void
    {
        $response = $this->post('/api/v1/register', [
            'username' => 'johndoe',
            'email' => 'john@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(201);
    }

    public function test_can_login()
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'user@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);
    }

    public function test_can_logout()
    {
        $token = $this->getBearerToken();

        $response = $this->withToken($token)->post('/api/v1/logout');

        $response->assertStatus(204);
    }
}
