<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingService
{
    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function update(array $data): Collection
    {
        /** @var \App\Models\Setting $frontendContent */
        $frontendContent = Setting::findOrFail('frontend_content');

        /** @var \App\Models\Setting $emailContent */
        $emailContent = Setting::findOrFail('email_content');

        $frontendContent->value = [
            'home_page' => [
                'title' => $data['frontend_content']['home_page']['title']
                    ?? $frontendContent->value['home_page']['title'],
            ],
        ];
        $frontendContent->save();

        $emailContent->value = [
            'admin' => [
                'new_contribution' => [
                    'subject' => $data['email_content']['admin']['new_contribution']['subject']
                        ?? $emailContent->value['admin']['new_contribution']['subject'],
                    'body' => $data['email_content']['admin']['new_contribution']['body']
                        ?? $emailContent->value['admin']['new_contribution']['body'],
                ],
                'updated_contribution' => [
                    'subject' => $data['email_content']['admin']['updated_contribution']['subject']
                        ?? $emailContent->value['admin']['updated_contribution']['subject'],
                    'body' => $data['email_content']['admin']['updated_contribution']['body']
                        ?? $emailContent->value['admin']['updated_contribution']['body'],
                ],
                'new_end_user' => [
                    'subject' => $data['email_content']['admin']['new_end_user']['subject']
                        ?? $emailContent->value['admin']['new_end_user']['subject'],
                    'body' => $data['email_content']['admin']['new_end_user']['body']
                        ?? $emailContent->value['admin']['new_end_user']['body'],
                ],
                'password_reset' => [
                    'subject' => $data['email_content']['admin']['password_reset']['subject']
                        ?? $emailContent->value['admin']['password_reset']['subject'],
                    'body' => $data['email_content']['admin']['password_reset']['body']
                        ?? $emailContent->value['admin']['password_reset']['body'],
                ],
            ],
            'end_user' => [
                'email_confirmation' => [
                    'subject' => $data['email_content']['end_user']['email_confirmation']['subject']
                        ?? $emailContent->value['end_user']['email_confirmation']['subject'],
                    'body' => $data['email_content']['end_user']['email_confirmation']['body']
                        ?? $emailContent->value['end_user']['email_confirmation']['body'],
                ],
                'password_reset' => [
                    'subject' => $data['email_content']['end_user']['password_reset']['subject']
                        ?? $emailContent->value['end_user']['password_reset']['subject'],
                    'body' => $data['email_content']['end_user']['password_reset']['body']
                        ?? $emailContent->value['end_user']['password_reset']['body'],
                ],
                'contribution_approved' => [
                    'subject' => $data['email_content']['end_user']['contribution_approved']['subject']
                        ?? $emailContent->value['end_user']['contribution_approved']['subject'],
                    'body' => $data['email_content']['end_user']['contribution_approved']['body']
                        ?? $emailContent->value['end_user']['contribution_approved']['body'],
                ],
                'contribution_rejected' => [
                    'subject' => $data['email_content']['end_user']['contribution_rejected']['subject']
                        ?? $emailContent->value['end_user']['contribution_rejected']['subject'],
                    'body' => $data['email_content']['end_user']['contribution_rejected']['body']
                        ?? $emailContent->value['end_user']['contribution_rejected']['body'],
                ],
            ],
        ];
        $emailContent->save();

        return Setting::all();
    }
}
