<?php

namespace Tests\Feature;

use Tests\TestCase;

class TagTest extends TestCase
{
    public function test_can_search_tags(): void
    {
        $response = $this->get('/api/v1/tags/search?q=API');

        $response->assertStatus(200);
    }
}
