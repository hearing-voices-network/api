<?php

declare(strict_types=1);

namespace App\Sms;

use App\VariableSubstitution\VariableSubstituter;
use Illuminate\Support\Facades\Config;

class TemplateSms extends GenericSms
{
    /**
     * @var \App\VariableSubstitution\VariableSubstituter
     */
    protected $substituter;

    /**
     * Dispatcher constructor.
     *
     * @param string $to
     * @param string $body
     * @param \App\VariableSubstitution\VariableSubstituter $substituter
     */
    public function __construct(string $to, string $body, VariableSubstituter $substituter)
    {
        parent::__construct($to, $body);

        $this->substituter = $substituter;
    }

    /**
     * Dispatch the email as a job to the queue.
     *
     * @param \App\Sms\SmsSender $sender
     */
    public function handle(SmsSender $sender): void
    {
        $sender->send(
            Config::get('sms.from'),
            $this->substituter->substitute($this->to),
            $this->substituter->substitute($this->body)
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
