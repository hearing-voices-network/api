<?php

declare(strict_types=1);

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            /*
             * Frontend content.
             */
            'frontend_content' => ['bail', 'array'],

            'frontend_content.home_page' => ['bail', 'array'],
            'frontend_content.home_page.title' => ['bail', 'string'],

            /*
             * Email content.
             */
            'email_content' => ['bail', 'array'],

            'email_content.admin' => ['bail', 'array'],

            'email_content.admin.new_contribution' => ['bail', 'array'],
            'email_content.admin.new_contribution.subject' => ['bail', 'string'],
            'email_content.admin.new_contribution.body' => ['bail', 'string'],

            'email_content.admin.updated_contribution' => ['bail', 'array'],
            'email_content.admin.updated_contribution.subject' => ['bail', 'string'],
            'email_content.admin.updated_contribution.body' => ['bail', 'string'],

            'email_content.admin.new_end_user' => ['bail', 'array'],
            'email_content.admin.new_end_user.subject' => ['bail', 'string'],
            'email_content.admin.new_end_user.body' => ['bail', 'string'],

            'email_content.admin.password_reset' => ['bail', 'array'],
            'email_content.admin.password_reset.subject' => ['bail', 'string'],
            'email_content.admin.password_reset.body' => ['bail', 'string'],

            'email_content.end_user' => ['bail', 'array'],

            'email_content.end_user.email_confirmation' => ['bail', 'array'],
            'email_content.end_user.email_confirmation.subject' => ['bail', 'string'],
            'email_content.end_user.email_confirmation.body' => ['bail', 'string'],

            'email_content.end_user.password_reset' => ['bail', 'array'],
            'email_content.end_user.password_reset.subject' => ['bail', 'string'],
            'email_content.end_user.password_reset.body' => ['bail', 'string'],

            'email_content.end_user.contribution_approved' => ['bail', 'array'],
            'email_content.end_user.contribution_approved.subject' => ['bail', 'string'],
            'email_content.end_user.contribution_approved.body' => ['bail', 'string'],

            'email_content.end_user.contribution_rejected' => ['bail', 'array'],
            'email_content.end_user.contribution_rejected.subject' => ['bail', 'string'],
            'email_content.end_user.contribution_rejected.body' => ['bail', 'string'],
        ];
    }
}
