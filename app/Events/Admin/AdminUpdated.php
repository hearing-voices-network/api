<?php

declare(strict_types=1);

namespace App\Events\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminUpdated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \App\Models\Admin
     */
    protected $admin;

    /**
     * AdminCreated constructor.
     *
     * @param \App\Models\Admin $admin
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return \App\Models\Admin
     */
    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}
