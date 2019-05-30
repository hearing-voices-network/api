<?php

declare(strict_types=1);

namespace Tests;

use App\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * Overridden from parent to provide custom TestResponse class.
     *
     * @param \Illuminate\Http\Response $response
     * @return \App\Foundation\Testing\TestResponse
     */
    protected function createTestResponse($response): TestResponse
    {
        return TestResponse::fromBaseResponse($response);
    }

    /**
     * Overridden from parent to type hint return of custom TestResponse class.
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return \App\Foundation\Testing\TestResponse
     */
    public function json($method, $uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::json($method, $uri, $data, $headers);
    }
}
