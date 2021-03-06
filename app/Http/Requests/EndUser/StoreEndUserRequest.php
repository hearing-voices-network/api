<?php

declare(strict_types=1);

namespace App\Http\Requests\EndUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rule;

class StoreEndUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['bail', 'required', 'email', 'max:255', 'unique:users'],
            'password' => ['bail', 'required', 'string', 'min:1', 'max:255'],
            'country' => ['bail', 'string', 'max:255'],
            'birth_year' => [
                'bail',
                'integer',
                Rule::min(Date::today()->year - Config::get('connecting_voices.age_requirement.max')),
                Rule::max(Date::today()->year - Config::get('connecting_voices.age_requirement.min')),
            ],
            'gender' => ['bail', 'string', 'max:255'],
            'ethnicity' => ['bail', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => sprintf(
                'The account has been withdrawn. Please contact the admin team via %s for more info.',
                config('connecting_voices.admin_email')
            ),
        ];
    }
}
