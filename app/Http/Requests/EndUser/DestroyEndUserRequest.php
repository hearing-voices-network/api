<?php

declare(strict_types=1);

namespace App\Http\Requests\EndUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DestroyEndUserRequest extends FormRequest
{
    const TYPE_SOFT_DELETE = 'soft_delete';
    const TYPE_FORCE_DELETE = 'force_delete';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => [
                'bail',
                'required',
                Rule::in([static::TYPE_SOFT_DELETE, static::TYPE_FORCE_DELETE]),
            ],
        ];
    }
}
