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
            'gdpr_consented_at' => $this->resource->gdpr_consented_at->toIso8601String(),
            'email_verified_at' => optional($this->resource->user->email_verified_at)->toIso8601String(),
            'created_at' => $this->resource->user->created_at->toIso8601String(),
            'updated_at' => $this->resource->user->updated_at->toIso8601String(),
            'deleted_at' => optional($this->resource->user->deleted_at)->toIso8601String(),
        ];
    }
}
