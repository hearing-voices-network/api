<?php

declare(strict_types=1);

namespace App\Http\Requests\Contribution;

use App\Models\Contribution;
use App\Rules\Words;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContributionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'content' => ['bail', 'string', 'max:10000', new Words()],
            'status' => [
                'bail',
                'string',
                Rule::in([
                    Contribution::STATUS_IN_REVIEW,
                    Contribution::STATUS_PRIVATE,
                ]),
            ],
            'tags' => ['bail', 'array'],
            'tags.*' => ['bail', 'array'],
            'tags.*.id' => ['bail', 'exists:tags,id', 'distinct'],
        ];
    }
}
