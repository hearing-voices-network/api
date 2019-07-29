<?php

declare(strict_types=1);

namespace App\Events\File;

use App\Models\File;
use App\Models\FileToken;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileRequested
{
    use Dispatchable;
    use SerializesModels;

    /**
     * @var \App\Models\File
     */
    protected $file;

    /**
     * @var \App\Models\FileToken
     */
    protected $fileToken;

    /**
     * FileCreated constructor.
     *
     * @param \App\Models\File $file
     * @param \App\Models\FileToken $fileToken
     */
    public function __construct(File $file, FileToken $fileToken)
    {
        $this->file = $file;
        $this->fileToken = $fileToken;
    }

    /**
     * @return \App\Models\File
     */
    public function getFile(): File
    {
        return $this->file;
    }

    /**
     * @return \App\Models\FileToken
     */
    public function getFileToken(): FileToken
    {
        return $this->fileToken;
    }
}
