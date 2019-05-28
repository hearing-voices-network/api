<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function example_works(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
