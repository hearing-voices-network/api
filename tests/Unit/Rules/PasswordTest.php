<?php

declare(strict_types=1);

namespace Tests\Unit\Rules;

use App\Rules\Password;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    /** @test */
    public function it_passes_strong_password(): void
    {
        $rule = new Password();

        $result = $rule->passes('test', '@bcd3fgH');

        $this->assertTrue($result);
    }

    /** @test */
    public function it_fails_weak_password(): void
    {
        $rule = new Password();

        $result = $rule->passes('test', 'secret');

        $this->assertFalse($result);
    }

    /** @test */
    public function message_is_correct(): void
    {
        $rule = new Password();
        $specialCharacters = Password::ALLOWED_SPECIAL_CHARACTERS;

        $message = <<<EOT
            The :attribute must be at least eight characters long, 
            contain one uppercase letter, 
            one lowercase letter, 
            one number and one special character ({$specialCharacters}).
            EOT;

        $this->assertEquals($message, $rule->message());
    }
}
