<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        $isAdmin = optional($request->user())->isAdmin();
        $isAuthor = optional($request->user())->isEndUser()
            && $this->resource->belongsToEndUser($request->user()->endUser);

        return [
            'id' => $this->id,
            'end_user_id' => $this->when($isAdmin || $isAuthor, $this->end_user_id),
            'content' => $this->content,
            'excerpt' => $this->resource->getExcerpt(),
            'status' => $this->status,
            'changes_requested' => $this->when(
                $isAdmin || $isAuthor,
                $this->changes_requested
            ),
            'status_last_updated_at' => $this->when(
                $isAdmin || $isAuthor,
                $this->status_last_updated_at->toIso8601String()
            ),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'tags' => TagResource::collection($this->tags),
        ];
    }
}
