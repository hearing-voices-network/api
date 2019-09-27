<?php

declare(strict_types=1);

namespace App\Http\Requests\EndUser;

use App\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
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
            'email' => [
                'bail',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->endUser->user_id),
            ],
            'password' => ['bail', 'string', 'max:255', new Password()],
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
}
