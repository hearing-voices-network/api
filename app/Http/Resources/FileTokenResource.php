<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\FileToken $resource
 */
class FileTokenResource extends JsonResource
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
            'token' => $this->resource->id,
            'download_url' => '', // TODO
            'expires_at' => $this->resource->created_at->addSeconds(
                config('connecting_voices.file_tokens.expiry_time')
            )->toIso8601String(),
        ];
    }
}
