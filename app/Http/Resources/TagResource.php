<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Tag $resource
 */
class TagResource extends JsonResource
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
            'parent_tag_id' => $this->resource->parent_tag_id,
            'name' => $this->resource->name,
            // This expects the count to already be appended.
            'public_contributions' => $this->resource->publicContributions()->count(),
            'created_at' => $this->resource->created_at->toIso8601String(),
            'updated_at' => $this->resource->updated_at->toIso8601String(),
            'deleted_at' => optional($this->resource->deleted_at)->toIso8601String(),
        ];
    }
}
