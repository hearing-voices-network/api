<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Events\Setting\SettingsUpdated;
use App\Services\SettingService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SettingServiceTest extends TestCase
{
    /** @test */
    public function it_updates_the_settings(): void
    {
        /** @var \App\Services\SettingService $settingService */
        $settingService = resolve(SettingService::class);

        $settings = $settingService->update([
            'frontend_content' => [
                'home_page' => [
                    'title' => 'frontend_content/home_page/title',
                ],
            ],
            'email_content' => [
                'admin' => [
                    'new_contribution' => [
                        'subject' => 'email_content/admin/new_contribution/subject',
                        'body' => 'email_content/admin/new_contribution/body',
                    ],
                    'updated_contribution' => [
                        'subject' => 'email_content/admin/updated_contribution/subject',
                        'body' => 'email_content/admin/updated_contribution/body',
                    ],
                    'new_end_user' => [
                        'subject' => 'email_content/admin/new_end_user/subject',
                        'body' => 'email_content/admin/new_end_user/body',
                    ],
                    'password_reset' => [
                        'subject' => 'email_content/admin/password_reset/subject',
                        'body' => 'email_content/admin/password_reset/body',
                    ],
                ],
                'end_user' => [
                    'email_confirmation' => [
                        'subject' => 'email_content/end_user/email_confirmation/subject',
                        'body' => 'email_content/end_user/email_confirmation/body',
                    ],
                    'password_reset' => [
                        'subject' => 'email_content/end_user/password_reset/subject',
                        'body' => 'email_content/end_user/password_reset/body',
                    ],
                    'contribution_approved' => [
                        'subject' => 'email_content/end_user/contribution_approved/subject',
                        'body' => 'email_content/end_user/contribution_approved/body',
                    ],
                    'contribution_rejected' => [
                        'subject' => 'email_content/end_user/contribution_rejected/subject',
                        'body' => 'email_content/end_user/contribution_rejected/body',
                    ],
                ],
            ],
        ]);

        $this->assertEquals($settings->find('frontend_content')->value, [
            'home_page' => [
                'title' => 'frontend_content/home_page/title',
            ],
        ]);
        $this->assertEquals($settings->find('email_content')->value, [
            'admin' => [
                'new_contribution' => [
                    'subject' => 'email_content/admin/new_contribution/subject',
                    'body' => 'email_content/admin/new_contribution/body',
                ],
                'updated_contribution' => [
                    'subject' => 'email_content/admin/updated_contribution/subject',
                    'body' => 'email_content/admin/updated_contribution/body',
                ],
                'new_end_user' => [
                    'subject' => 'email_content/admin/new_end_user/subject',
                    'body' => 'email_content/admin/new_end_user/body',
                ],
                'password_reset' => [
                    'subject' => 'email_content/admin/password_reset/subject',
                    'body' => 'email_content/admin/password_reset/body',
                ],
            ],
            'end_user' => [
                'email_confirmation' => [
                    'subject' => 'email_content/end_user/email_confirmation/subject',
                    'body' => 'email_content/end_user/email_confirmation/body',
                ],
                'password_reset' => [
                    'subject' => 'email_content/end_user/password_reset/subject',
                    'body' => 'email_content/end_user/password_reset/body',
                ],
                'contribution_approved' => [
                    'subject' => 'email_content/end_user/contribution_approved/subject',
                    'body' => 'email_content/end_user/contribution_approved/body',
                ],
                'contribution_rejected' => [
                    'subject' => 'email_content/end_user/contribution_rejected/subject',
                    'body' => 'email_content/end_user/contribution_rejected/body',
                ],
            ],
        ]);
    }

    /** @test */
    public function it_dispatches_an_event_when_updated(): void
    {
        Event::fake([SettingsUpdated::class]);

        /** @var \App\Services\SettingService $settingService */
        $settingService = resolve(SettingService::class);

        $settings = $settingService->update([]);

        Event::assertDispatched(
            SettingsUpdated::class,
            function (SettingsUpdated $event) use ($settings): bool {
                foreach ($event->getSetting() as $eventSetting) {
                    /** @var \App\Models\Setting $eventSetting */
                    /** @var \App\Models\Setting|null $setting */
                    $setting = $settings->firstWhere('key', $eventSetting->key);

                    if ($setting === null) {
                        return false;
                    }

                    if ($setting->isNot($eventSetting)) {
                        return false;
                    }
                }

                return true;
            }
        );
    }
}
