<?php

declare(strict_types=1);

namespace App\Mail;

use App\VariableSubstitution\VariableSubstituter;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Message;

class TemplateMail extends GenericMail
{
    /**
     * @var \App\VariableSubstitution\VariableSubstituter
     */
    protected $substituter;

    /**
     * Dispatcher constructor.
     *
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param \App\VariableSubstitution\VariableSubstituter $substituter
     */
    public function __construct(
        string $to,
        string $subject,
        string $body,
        VariableSubstituter $substituter
    ) {
        parent::__construct($to, $subject, $body);

        $this->substituter = $substituter;
    }

    /**
     * Dispatch the email as a job to the queue.
     *
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     */
    public function handle(Mailer $mailer): void
    {
        $mailer->raw(
            $this->substituter->substitute($this->body),
            function (Message $message): void {
                $message
                    ->to($this->to)
                    ->subject($this->substituter->substitute($this->subject));
            }
        );
    }

    /**
     * @return \App\VariableSubstitution\VariableSubstituter
     */
    public function getSubstituter(): VariableSubstituter
    {
        return $this->substituter;
    }
}
