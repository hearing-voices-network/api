<?php

declare(strict_types=1);

namespace Tests\Support;

use Illuminate\Foundation\Testing\Assert as PHPUnit;
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
     * @param int $index
     * @param string $id
     */
    public function assertNthIdInCollection(int $index, string $id): void
    {
        $data = $this->getData();

        PHPUnit::assertGreaterThanOrEqual($index + 1, count($data));
        PHPUnit::assertEquals($id, $data[$index]['id']);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return json_decode($this->getContent(), true)['data'];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->getData()['id'];
    }
}
