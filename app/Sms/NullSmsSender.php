<?php

declare(strict_types=1);

namespace App\Sms;

class NullSmsSender implements SmsSender
{
    /**
     * Sends an SMS.
     *
     * @param string $from
     * @param string $to
     * @param string $body
     */
    public function send(string $from, string $to, string $body): void
    {
        return;
    }
}
