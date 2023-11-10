<?php

namespace Tests\Feature;

use Tests\TestCase;

class StackTest extends TestCase
{
    public function test_can_search_stacks(): void
    {
        $response = $this->get('/api/v1/tech-stacks/search?q=html');

        $response->assertStatus(200);
    }
}
