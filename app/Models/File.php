<?php

declare(strict_types=1);

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class File extends Model implements Responsable
{
    use Mutators\FileMutators;
    use Relationships\FileRelationships;
    use Scopes\FileScopes;

    const MIME_TYPE_PNG = 'image/png';
    const MIME_TYPE_JPEG = 'image/jpeg';

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request): Response
    {
        return response()->make($this->getContent(), Response::HTTP_OK, [
            'Content-Type' => $this->mime_type,
            'Content-Disposition' => sprintf('inline; filename="%s"', $this->filename),
        ]);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return Storage::cloud()->get($this->path());
    }

    /**
     * @return string
     */
    public function path(): string
    {
        $directory = $this->is_private ? 'files/private' : 'files/public';

        return "/{$directory}/{$this->id}.dat";
    }

    /**
     * @return string
     */
    protected function visibility(): string
    {
        return $this->is_private ? 'private' : 'public';
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return Storage::cloud()->url($this->path());
    }

    /**
     * @param string $content
     * @return \App\Models\File
     */
    public function upload(string $content): self
    {
        Storage::cloud()->put($this->path(), $content, $this->visibility());

        return $this;
    }

    /**
     * @param string $content
     * @return \App\Models\File
     */
    public function uploadBase64EncodedFile(string $content): self
    {
        $data = explode(',', $content);
        $data = base64_decode(end($data));

        return $this->upload($data);
    }

    /**
     * Deletes the file from disk.
     */
    public function deleteFromDisk(): void
    {
        Storage::cloud()->delete($this->path());
    }
}
