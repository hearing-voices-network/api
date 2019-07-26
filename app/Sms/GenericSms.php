<?php

declare(strict_types=1);

namespace App\Sms;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class GenericSms implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $body;

    /**
     * Dispatcher constructor.
     *
     * @param string $to
     * @param string $body
     */
    public function __construct(string $to, string $body)
    {
        $this->to = $to;
        $this->body = $body;
    }

    /**
     * Dispatch the email as a job to the queue.
     *
     * @param \App\Sms\SmsSender $sender
     */
    public function handle(SmsSender $sender): void
    {
        $sender->send((string)config('sms.from'), $this->to, $this->body);
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
