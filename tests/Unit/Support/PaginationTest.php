<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Pagination;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    /** @test */
    public function ten_returns_ten(): void
    {
        $pagination = new Pagination();

        $this->assertEquals(
            10,
            $pagination->perPage(10)
        );
    }

    /** @test */
    public function one_returns_one(): void
    {
        $pagination = new Pagination();

        $this->assertEquals(
            1,
            $pagination->perPage(1)
        );
    }

    /** @test */
    public function max_returns_max(): void
    {
        $pagination = new Pagination();

        $this->assertEquals(
            (int)config('connecting_voices.pagination.max'),
            $pagination->perPage((int)config('connecting_voices.pagination.max'))
        );
    }

    /** @test */
    public function zero_returns_one(): void
    {
        $pagination = new Pagination();

        $this->assertEquals(
            1,
            $pagination->perPage(0)
        );
    }

    /** @test */
    public function one_more_than_max_returns_max(): void
    {
        $pagination = new Pagination();

        $this->assertEquals(
            (int)config('connecting_voices.pagination.max'),
            $pagination->perPage((int)config('connecting_voices.pagination.max') + 1)
        );
    }
}
