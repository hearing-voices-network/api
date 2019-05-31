<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Foundation\Http\FormRequest;
use App\Rules\Password;
use App\Rules\UkPhoneNumber;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['bail', 'string', 'max:255'],
            'phone' => ['bail', 'string', 'max:255', new UkPhoneNumber()],
            'email' => ['bail', 'email', 'max:255'],
            'password' => ['bail', 'string', 'max:255', new Password()],
        ];
    }
}
