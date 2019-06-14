<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Admin;
use App\Models\File;
use App\Models\FileToken;
use Illuminate\Support\Facades\Date;

class FileService
{
    /**
     * @param \App\Models\File $file
     * @param \App\Models\Admin $admin
     * @return \App\Models\FileToken
     */
    public function request(File $file, Admin $admin): FileToken
    {
        /** @var \App\Models\FileToken $fileToken */
        $fileToken = $file->fileTokens()->create([
            'user_id' => $admin->user_id,
            'created_at' => Date::now(),
        ]);

        return $fileToken;
    }
}
