<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Contribution $resource
 */
class ContributionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $isAdmin = optional($request->user('api'))->isAdmin();
        $isAuthor = optional($request->user('api'))->isEndUser()
            && $this->resource->belongsToEndUser($request->user('api')->endUser);

        return [
            'id' => $this->resource->id,
            'end_user_id' => $this->when($isAdmin || $isAuthor, $this->resource->end_user_id),
            'content' => $this->resource->content,
            'excerpt' => $this->resource->getExcerpt(),
            'status' => $this->resource->status,
            'changes_requested' => $this->when(
                $isAdmin || $isAuthor,
                $this->resource->changes_requested
            ),
            'status_last_updated_at' => $this->when(
                $isAdmin || $isAuthor,
                $this->resource->status_last_updated_at->toIso8601String()
            ),
            'created_at' => $this->resource->created_at->toIso8601String(),
            'updated_at' => $this->resource->updated_at->toIso8601String(),
            'tags' => TagResource::collection($this->resource->tags),
        ];
    }
}
