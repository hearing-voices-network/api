<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

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

        $contentValue = function (string $key, Setting $content) use ($data) {
            return Arr::get($data, $key, Arr::get($content->value, $key));
        };

        $frontendContentValue = function (string $key) use ($contentValue, $frontendContent) {
            return $contentValue("frontend_content.{$key}", $frontendContent);
        };

        $emailContentValue = function (string $key) use ($contentValue, $emailContent) {
            return $contentValue("email_content.{$key}", $emailContent);
        };

        $frontendContent->value = [
            'home_page' => [
                'title' => $frontendContentValue('home_page.title'),
            ],
        ];
        $frontendContent->save();

        $emailContent->value = [
            'admin' => [
                'new_contribution' => [
                    'subject' => $emailContentValue('admin.new_contribution.subject'),
                    'body' => $emailContentValue('admin.new_contribution.body'),
                ],
                'updated_contribution' => [
                    'subject' => $emailContentValue('admin.updated_contribution.subject'),
                    'body' => $emailContentValue('admin.updated_contribution.body'),
                ],
                'new_end_user' => [
                    'subject' => $emailContentValue('admin.new_end_user.subject'),
                    'body' => $emailContentValue('admin.new_end_user.body'),
                ],
                'password_reset' => [
                    'subject' => $emailContentValue('admin.password_reset.subject'),
                    'body' => $emailContentValue('admin.password_reset.body'),
                ],
            ],
            'end_user' => [
                'email_confirmation' => [
                    'subject' => $emailContentValue('end_user.email_confirmation.subject'),
                    'body' => $emailContentValue('end_user.email_confirmation.body'),
                ],
                'password_reset' => [
                    'subject' => $emailContentValue('end_user.password_reset.subject'),
                    'body' => $emailContentValue('end_user.password_reset.body'),
                ],
                'contribution_approved' => [
                    'subject' => $emailContentValue('end_user.contribution_approved.subject'),
                    'body' => $emailContentValue('end_user.contribution_approved.body'),
                ],
                'contribution_rejected' => [
                    'subject' => $emailContentValue('end_user.contribution_rejected.subject'),
                    'body' => $emailContentValue('end_user.contribution_rejected.body'),
                ],
            ],
        ];
        $emailContent->save();

        return Setting::all();
    }
}
