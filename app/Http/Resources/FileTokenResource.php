<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;

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
        $fileTokenId = $this->resource->id;
        $fileId = $this->resource->file_id;

        return [
            'token' => $this->resource->id,
            'download_url' => route('files.download', $fileId) . "?token={$fileTokenId}",
            'expires_at' => $this->resource->created_at->addSeconds(
                Config::get('connecting_voices.file_tokens.expiry_time')
            )->toIso8601String(),
        ];
    }
}
