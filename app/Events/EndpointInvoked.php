<?php

namespace App\Events;

use App\Models\Audit;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Client;

class EndpointInvoked
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \App\Models\User|null
     */
    protected $user;

    /**
     * @var \Laravel\Passport\Client|null
     */
    protected $client;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string
     */
    protected $ipAddress;

    /**
     * @var string|null
     */
    protected $userAgent;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \Carbon\CarbonImmutable
     */
    protected $createdAt;

    /**
     * EndpointInvoked constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $action
     * @param string|null $description
     * @param \Illuminate\Database\Eloquent\Model|null $model
     */
    protected function __construct(
        Request $request,
        string $action,
        string $description = null,
        Model $model = null
    ) {
        $this->user = $request->user();
        $this->client = optional($this->user)->token()->client ?? null;
        $this->action = $action;
        $this->description = $description;
        $this->ipAddress = $request->ip();
        $this->userAgent = $request->userAgent() ?: null;
        $this->model = $model;
        $this->createdAt = Date::now();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string|null $description
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \App\Events\EndpointInvoked
     */
    public static function onLogin(
        Request $request,
        string $description = null,
        Model $model = null
    ): self {
        return new static($request, Audit::ACTION_LOGIN, $description, $model);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string|null $description
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \App\Events\EndpointInvoked
     */
    public static function onLogout(
        Request $request,
        string $description = null,
        Model $model = null
    ): self {
        return new static($request, Audit::ACTION_LOGOUT, $description, $model);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string|null $description
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \App\Events\EndpointInvoked
     */
    public static function onCreate(
        Request $request,
        string $description = null,
        Model $model = null
    ): self {
        return new static($request, Audit::ACTION_CREATE, $description, $model);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string|null $description
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \App\Events\EndpointInvoked
     */
    public static function onRead(
        Request $request,
        string $description = null,
        Model $model = null
    ): self {
        return new static($request, Audit::ACTION_READ, $description, $model);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string|null $description
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \App\Events\EndpointInvoked
     */
    public static function onUpdate(
        Request $request,
        string $description = null,
        Model $model = null
    ): self {
        return new static($request, Audit::ACTION_UPDATE, $description, $model);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string|null $description
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \App\Events\EndpointInvoked
     */
    public static function onDelete(
        Request $request,
        string $description = null,
        Model $model = null
    ): self {
        return new static($request, Audit::ACTION_DELETE, $description, $model);
    }

    /**
     * @return \App\Models\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return \Laravel\Passport\Client|null
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * @return string|null
     */
    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * @return \Carbon\CarbonImmutable
     */
    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }
}
