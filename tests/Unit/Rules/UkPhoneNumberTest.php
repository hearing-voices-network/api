<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Rules\UkPhoneNumber;
use Tests\TestCase;

class UkPhoneNumberTest extends TestCase
{
    /** @test */
    public function it_passes_mobile_number(): void
    {
        $rule = new UkPhoneNumber();

        $result = $rule->passes('test', '07700000000');

        $this->assertTrue($result);
    }

    /** @test */
    public function it_passes_landline_number(): void
    {
        $rule = new UkPhoneNumber();

        $result = $rule->passes('test', '01130000000');

        $this->assertTrue($result);
    }

    /** @test */
    public function it_fails_invalid_number(): void
    {
        $rule = new UkPhoneNumber();

        $result = $rule->passes('test', '11111111111');

        $this->assertfalse($result);
    }

    /** @test */
    public function message_is_correct(): void
    {
        $rule = new UkPhoneNumber();

        $this->assertEquals('The :attribute must be a valid UK phone number.', $rule->message());
    }
}
