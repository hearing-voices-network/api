<?php

declare(strict_types=1);

namespace App\Http\Requests\Export;

use App\Exporters\AllExporter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestExportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'export' => ['bail', 'string', Rule::in([AllExporter::type()])],
        ];
    }
}
