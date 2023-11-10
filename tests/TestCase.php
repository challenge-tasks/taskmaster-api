<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate --seed');
        $this->artisan('db:seed FakeSeeder');
        $this->artisan('passport:install');
    }

    protected function getBearerToken(): string
    {
        $response = $this->post('/api/v1/login', [
            'email' => 'user@gmail.com',
            'password' => 'password'
        ]);

        return $response['data']['token'];
    }
}
