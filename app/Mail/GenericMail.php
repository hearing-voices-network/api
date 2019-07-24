<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class GenericMail extends Mailable
{
    use Queueable;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $content;

    /**
     * NewContribution constructor.
     *
     * @param string $subject
     * @param string $content
     */
    public function __construct(string $subject, string $content)
    {
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return \App\Mail\GenericMail
     */
    public function build(): self
    {
        return $this
            ->subject($this->subject)
            ->text('mail.generic', ['content' => $this->content]);
    }
}
