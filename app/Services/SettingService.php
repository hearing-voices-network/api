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

        // Update the frontend content settings.
        $frontendContent->value = [
            'home_page' => [
                'title' => $this->frontendContentValue($data, 'home_page.title'),
            ],
        ];
        $frontendContent->save();

        // Update the email content settings.
        $emailContent->value = [
            'admin' => [
                'new_contribution' => [
                    'subject' => $this->emailContentValue($data, 'admin.new_contribution.subject'),
                    'body' => $this->emailContentValue($data, 'admin.new_contribution.body'),
                ],
                'updated_contribution' => [
                    'subject' => $this->emailContentValue($data, 'admin.updated_contribution.subject'),
                    'body' => $this->emailContentValue($data, 'admin.updated_contribution.body'),
                ],
                'new_end_user' => [
                    'subject' => $this->emailContentValue($data, 'admin.new_end_user.subject'),
                    'body' => $this->emailContentValue($data, 'admin.new_end_user.body'),
                ],
                'password_reset' => [
                    'subject' => $this->emailContentValue($data, 'admin.password_reset.subject'),
                    'body' => $this->emailContentValue($data, 'admin.password_reset.body'),
                ],
            ],
            'end_user' => [
                'email_confirmation' => [
                    'subject' => $this->emailContentValue($data, 'end_user.email_confirmation.subject'),
                    'body' => $this->emailContentValue($data, 'end_user.email_confirmation.body'),
                ],
                'password_reset' => [
                    'subject' => $this->emailContentValue($data, 'end_user.password_reset.subject'),
                    'body' => $this->emailContentValue($data, 'end_user.password_reset.body'),
                ],
                'contribution_approved' => [
                    'subject' => $this->emailContentValue($data, 'end_user.contribution_approved.subject'),
                    'body' => $this->emailContentValue($data, 'end_user.contribution_approved.body'),
                ],
                'contribution_rejected' => [
                    'subject' => $this->emailContentValue($data, 'end_user.contribution_rejected.subject'),
                    'body' => $this->emailContentValue($data, 'end_user.contribution_rejected.body'),
                ],
            ],
        ];
        $emailContent->save();

        return Setting::all();
    }

    /**
     * Helper function for getting the value of content settings.
     *
     * @param array $data
     * @param string $settingKey
     * @param string $nestedKey
     * @return string
     */
    protected function contentValue(array $data, string $settingKey, string $nestedKey): string
    {
        $setting = Setting::findOrFail($settingKey);

        return Arr::get($data, "{$settingKey}.{$nestedKey}") ?? Arr::get($setting->value, $nestedKey);
    }

    /**
     * @param array $data
     * @param string $key
     * @return string
     */
    protected function frontendContentValue(array $data, string $key): string
    {
        return $this->contentValue($data, 'frontend_content', $key);
    }

    /**
     * @param array $data
     * @param string $key
     * @return string
     */
    protected function emailContentValue(array $data, string $key): string
    {
        return $this->contentValue($data, 'email_content', $key);
    }
}
