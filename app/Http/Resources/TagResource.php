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
            'public_contributions' => $this->getPublicContributionsCount(),
            'created_at' => $this->resource->created_at->toIso8601String(),
            'updated_at' => $this->resource->updated_at->toIso8601String(),
            'deleted_at' => optional($this->resource->deleted_at)->toIso8601String(),
        ];
    }

    /**
     * First attempts to use the relationship count attribute if appended.
     * Then attempt to use the count of the loaded relationships.
     * Finally resorts to querying the database for the count.
     *
     * @return int
     */
    protected function getPublicContributionsCount(): int
    {
        return $this->public_contributions_count ?? (int)$this->whenLoaded(
                'publicContributions',
                count($this->resource->publicContributions),
                $this->resource->publicContributions()->count()
            );
    }
}
