<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Rules\Password;
use App\Rules\UkPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'max:255'],
            'phone' => ['bail', 'required', 'string', 'max:255', new UkPhoneNumber()],
            'email' => ['bail', 'required', 'email', 'max:255'],
            'password' => ['bail', 'required', 'string', 'max:255', new Password()],
        ];
    }
}
