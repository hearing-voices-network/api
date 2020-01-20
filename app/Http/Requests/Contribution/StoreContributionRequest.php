<?php

declare(strict_types=1);

namespace App\Http\Requests\Contribution;

use App\Models\Contribution;
use App\Rules\Words;
use App\Support\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContributionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @throws \ReflectionException
     * @return array
     */
    public function rules(): array
    {
        return [
            'content' => ['bail', 'required', 'string', 'max:10000', new Words()],
            'status' => [
                'bail',
                'required',
                'string',
                Rule::in(
                    (new Enum(Contribution::class))->getValues('STATUS')
                ),
            ],
            'tags' => ['bail', 'present', 'array'],
            'tags.*' => ['bail', 'array'],
            'tags.*.id' => ['bail', 'exists:tags,id', 'distinct'],
        ];
    }
}
