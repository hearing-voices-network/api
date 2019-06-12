<?php

declare(strict_types=1);

namespace App\Http\Requests\EndUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexEndUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'filter[email_verified]' => ['bail', Rule::in(['true', 'false', 'all'])],
            'filter[with_soft_deletes]' => ['bail', Rule::in(['true', 'false'])],
        ];
    }
}
