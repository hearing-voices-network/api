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
     * Visit the given URI with a GET request, expecting a JSON response.
     *
     * @param string $uri
     * @param array $headers
     * @return \App\Foundation\Testing\TestResponse
     */
    public function getJson($uri, array $headers = []): TestResponse
    {
        return parent::getJson($uri, $headers);
    }

    /**
     * Visit the given URI with a POST request, expecting a JSON response.
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return \App\Foundation\Testing\TestResponse
     */
    public function postJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::postJson($uri, $data, $headers);
    }

    /**
     * Visit the given URI with a PUT request, expecting a JSON response.
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return \App\Foundation\Testing\TestResponse
     */
    public function putJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::putJson($uri, $data, $headers);
    }

    /**
     * Visit the given URI with a PATCH request, expecting a JSON response.
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return \App\Foundation\Testing\TestResponse
     */
    public function patchJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::patchJson($uri, $data, $headers);
    }

    /**
     * Visit the given URI with a DELETE request, expecting a JSON response.
     *
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return \App\Foundation\Testing\TestResponse
     */
    public function deleteJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::deleteJson($uri, $data, $headers);
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
