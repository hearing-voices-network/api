<?php

declare(strict_types=1);

namespace App\Http\Requests\Contribution;

use App\Foundation\Http\FormRequest;

class RejectContributionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'changes_requested' => ['bail', 'required', 'string', 'max:1000'],
        ];
    }
}
