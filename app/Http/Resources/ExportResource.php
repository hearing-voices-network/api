<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Export $resource
 */
class ExportResource extends JsonResource
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
            'decryption_key' => $this->resource->decryptionKey,
            'token' => $this->resource->fileToken->id,
            'download_url' => '', // TODO
            'expires_at' => $this->resource->fileToken->created_at->addSeconds(
                config('connecting_voices.file_tokens.expiry_time')
            )->toIso8601String(),
        ];
    }
}
