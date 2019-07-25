<?php

declare(strict_types=1);

namespace App\Events\EndUser;

use App\Models\EndUser;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EndUserCreated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \App\Models\EndUser
     */
    protected $endUser;

    /**
     * EndUserCreated constructor.
     *
     * @param \App\Models\EndUser $endUser
     */
    public function __construct(EndUser $endUser)
    {
        $this->endUser = $endUser;
    }

    /**
     * @return \App\Models\EndUser
     */
    public function getEndUser(): EndUser
    {
        return $this->endUser;
    }
}
