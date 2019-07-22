<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Enum;
use Tests\TestCase;

class EnumTest extends TestCase
{
    const TEST_ONE = 'one';
    const TEST_TWO = 'two';
    const DIFFERENT_THREE = 'three';

    /** @test */
    public function class_with_no_constants(): void
    {
        $noConstants = new class {};

        $enum = new Enum($noConstants);
        $constants = $enum->get('');

        $this->assertCount(0, $constants);
    }

    /** @test */
    public function fully_qualified_class_name_works(): void
    {
        $enum = new Enum(static::class);
        $constants = $enum->get('TEST');

        $this->assertCount(2, $constants);
        $this->assertEquals([
            'TEST_ONE' => 'one',
            'TEST_TWO' => 'two',
        ], $constants);
    }

    /** @test */
    public function instance_works(): void
    {
        $enum = new Enum($this);
        $constants = $enum->get('TEST');

        $this->assertCount(2, $constants);
        $this->assertEquals([
            'TEST_ONE' => 'one',
            'TEST_TWO' => 'two',
        ], $constants);
    }

    /** @test */
    public function get_values_works(): void
    {
        $enum = new Enum($this);
        $constants = $enum->getValues('TEST');

        $this->assertCount(2, $constants);
        $this->assertEquals([0 => 'one', 1 => 'two'], $constants);
    }

    /** @test */
    public function get_keys_works(): void
    {
        $enum = new Enum($this);
        $constants = $enum->getkeys('TEST');

        $this->assertCount(2, $constants);
        $this->assertEquals([0 => 'TEST_ONE', 1 => 'TEST_TWO'], $constants);
    }
}
