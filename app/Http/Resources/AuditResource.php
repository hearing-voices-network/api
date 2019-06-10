<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Audit $resource
 */
class AuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'admin_id' => $this->resource->user->admin->id ?? null,
            'end_user_id' => $this->resource->user->endUser->id ?? null,
            'client' => $this->resource->client->name ?? null,
            'action' => $this->resource->action,
            'description' => $this->resource->description,
            'ip_address' => $this->resource->ip_address,
            'user_agent' => $this->resource->user_agent,
            'created_at' => $this->resource->created_at->toIso8601String(),
        ];
    }
}
