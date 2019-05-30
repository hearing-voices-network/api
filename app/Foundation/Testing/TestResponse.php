<?php

declare(strict_types=1);

namespace App\Foundation\Testing;

use Illuminate\Foundation\Testing\TestResponse as BaseTestResponse;

class TestResponse extends BaseTestResponse
{
    /**
     * Assert the JSON structure of an Eloquent API Resource Collection.
     *
     * @param array|null $structure
     */
    public function assertCollectionDataStructure(array $structure = null): void
    {
        $this->assertJsonStructure([
            'data' => [$structure],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
        ]);
    }

    /**
     * @param array|null $structure
     */
    public function assertResourceDataStructure(array $structure = null): void
    {
        $this->assertJsonStructure(['data' => $structure]);
    }

    /**
     * @param array|null $structure
     */
    public function assertResourceData(array $structure = null): void
    {
        $this->assertJsonFragment(['data' => $structure]);
    }
}
