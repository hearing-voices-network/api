<?php

declare(strict_types=1);

namespace App\Http\Requests\EndUser;

use App\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEndUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['bail', 'email', 'max:255'],
            'password' => ['bail', 'string', 'max:255', new Password()],
            'country' => ['bail', 'string', 'max:255'],
            'birth_year' => [
                'bail',
                'integer',
                Rule::min(today()->year - config('connecting_voices.age_requirement.max')),
                Rule::max(today()->year - config('connecting_voices.age_requirement.min')),
            ],
            'gender' => ['bail', 'string', 'max:255'],
            'ethnicity' => ['bail', 'string', 'max:255'],
        ];
    }
}