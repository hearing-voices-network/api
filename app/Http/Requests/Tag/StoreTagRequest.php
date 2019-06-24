<?php

declare(strict_types=1);

namespace App\Http\Requests\Tag;

use App\Rules\ParentTagIsTopLevel;
use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'parent_tag_id' => ['bail', 'present', 'nullable', 'exists:tags,id', new ParentTagIsTopLevel()],
            'name' => ['bail', 'required', 'string', 'max:255'],
        ];
    }
}
