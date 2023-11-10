<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function test_can_get_profile(): void
    {
        $token = $this->getBearerToken();

        $response = $this->withToken($token)->get('/api/v1/profile');

        $response->assertStatus(200);
    }

    public function test_can_update_profile()
    {
        $token = $this->getBearerToken();

        $response = $this->withToken($token)->put('/api/v1/profile', [
            'username' => 'marydoe',
            'email' => 'mary@gmail.com'
        ]);

        $response->assertStatus(200);
    }

    public function test_can_update_password()
    {
        $token = $this->getBearerToken();

        $response = $this->withToken($token)->put('/api/v1/profile/password', [
            'password' => 'new_password',
            'password_confirmation' => 'new_password'
        ]);

        $response->assertStatus(200);
    }
}
