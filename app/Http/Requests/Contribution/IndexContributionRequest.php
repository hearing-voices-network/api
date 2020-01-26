<?php

declare(strict_types=1);

namespace App\Http\Requests\Contribution;

use Illuminate\Foundation\Http\FormRequest;

class IndexContributionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'filter[end_user_id]' => [
                'bail',
                function (string $attribute, string $endUserIds, callable $fail): void {
                    if (!$this->user('api')->isEndUser()) {
                        return;
                    }

                    foreach (explode(',', $endUserIds) as $endUserId) {
                        if ($this->user('api')->endUser->id !== $endUserId) {
                            $fail('End users can only filter by their own ID.');
                        }
                    }
                },
            ],
        ];
    }
}
