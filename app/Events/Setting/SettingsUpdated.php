<?php

declare(strict_types=1);

namespace App\Events\Setting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettingsUpdated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $settings;

    /**
     * SettingCreated constructor.
     *
     * @param \Illuminate\Database\Eloquent\Collection $settings
     */
    public function __construct(Collection $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSetting(): Collection
    {
        return $this->settings;
    }
}
