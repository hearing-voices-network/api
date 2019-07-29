<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;

class GenericMail implements ShouldQueue
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
    protected $subject;

    /**
     * @var string
     */
    protected $body;

    /**
     * Dispatcher constructor.
     *
     * @param string $to
     * @param string $subject
     * @param string $body
     */
    public function __construct(string $to, string $subject, string $body)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Dispatch the email as a job to the queue.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     */
    public function handle(Mailer $mailer): void
    {
        $mailer->raw(
            $this->body,
            function (Message $message): void {
                $message
                    ->to($this->to)
                    ->subject($this->subject);
            }
        );
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
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
