<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\EndUser $resource
 */
class EndUserResource extends JsonResource
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
            'email' => $this->resource->user->email,
            'country' => $this->resource->country,
            'birth_year' => $this->resource->birth_year,
            'gender' => $this->resource->gender,
            'ethnicity' => $this->resource->ethnicity,
            'contributions_count' => $this->getContributionsCount(),
            'public_contributions_count' => $this->getPublicContributionsCount(),
            'private_contributions_count' => $this->getPrivateContributionsCount(),
            'in_review_contributions_count' => $this->getInReviewContributionsCount(),
            'changes_requested_contributions_count' => $this->getChangesRequestedContributionsCount(),
            'gdpr_consented_at' => $this->resource->gdpr_consented_at->toIso8601String(),
            'email_verified_at' => optional($this->resource->user->email_verified_at)->toIso8601String(),
            'created_at' => $this->resource->user->created_at->toIso8601String(),
            'updated_at' => $this->resource->user->updated_at->toIso8601String(),
            'deleted_at' => optional($this->resource->user->deleted_at)->toIso8601String(),
        ];
    }

    /**
     * First attempts to use the relationship count attribute if appended.
     * Then attempt to use the count of the loaded relationships.
     * Finally resorts to querying the database for the count.
     *
     * @return int
     */
    protected function getContributionsCount(): int
    {
        return $this->contributions_count ?? (int)$this->whenLoaded(
            'contributions',
            count($this->resource->contributions),
            $this->resource->contributions()->count()
            );
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

    /**
     * First attempts to use the relationship count attribute if appended.
     * Then attempt to use the count of the loaded relationships.
     * Finally resorts to querying the database for the count.
     *
     * @return int
     */
    protected function getPrivateContributionsCount(): int
    {
        return $this->private_contributions_count ?? (int)$this->whenLoaded(
            'privateContributions',
            count($this->resource->privateContributions),
            $this->resource->privateContributions()->count()
            );
    }

    /**
     * First attempts to use the relationship count attribute if appended.
     * Then attempt to use the count of the loaded relationships.
     * Finally resorts to querying the database for the count.
     *
     * @return int
     */
    protected function getInReviewContributionsCount(): int
    {
        return $this->in_review_contributions_count ?? (int)$this->whenLoaded(
            'inReviewContributions',
            count($this->resource->inReviewContributions),
            $this->resource->inReviewContributions()->count()
            );
    }

    /**
     * First attempts to use the relationship count attribute if appended.
     * Then attempt to use the count of the loaded relationships.
     * Finally resorts to querying the database for the count.
     *
     * @return int
     */
    protected function getChangesRequestedContributionsCount(): int
    {
        return $this->changes_requested_contributions_count ?? (int)$this->whenLoaded(
            'changesRequestedContributions',
            count($this->resource->changesRequestedContributions),
            $this->resource->changesRequestedContributions()->count()
            );
    }
}
