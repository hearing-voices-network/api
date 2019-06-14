<?php

declare(strict_types=1);

namespace App\Http\Requests\File;

use App\Rules\ValidFileToken;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DownloadFileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        /** @var \App\Models\File $file */
        $file = $this->route('file');

        /** @var \App\Models\Admin|null $admin */
        $admin = optional($this->user())->admin;

        return [
            'token' => [
                'bail',
                Rule::requiredIf(function (): bool {
                    /** @var \App\Models\File $file */
                    $file = $this->route('file');

                    return $file->isPrivate();
                }),
                'string',
                'exists:file_tokens,id',
                new ValidFileToken($file, $admin),
            ],
        ];
    }
}
