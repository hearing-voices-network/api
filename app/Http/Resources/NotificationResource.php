<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Notification $resource
 */
class NotificationResource extends JsonResource
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
            'channel' => $this->resource->channel,
            'recipient' => $this->resource->recipient,
            'content' => $this->resource->content,
            'sent_at' => optional($this->resource->sent_at)->toIso8601String(),
            'created_at' => $this->resource->created_at->toIso8601String(),
            'updated_at' => $this->resource->updated_at->toIso8601String(),
        ];
    }
}
