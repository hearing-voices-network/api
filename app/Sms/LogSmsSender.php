<?php

declare(strict_types=1);

namespace App\Sms;

class LogSmsSender implements SmsSender
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
        logger()->debug("Sms sent from [{$from}] to [{$to}] with message [{$body}].");
    }
}
