<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Models\Admin;
use App\Models\EndUser;
use App\Models\Setting;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SettingControllerTest extends TestCase
{
    /*
     * Index.
     */

    /** @test */
    public function guest_can_index(): void
    {
        $response = $this->getJson('/v1/settings');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function end_user_can_index(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson('/v1/settings');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function admin_can_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/settings');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/settings');

        $response->assertJsonStructure([
            'data' => [
                'frontend_content' => [
                    'home_page' => [
                        'title',
                    ],
                ],
                'email_content' => [
                    'admin' => [
                        'new_contribution' => [
                            'subject',
                            'body',
                        ],
                        'updated_contribution' => [
                            'subject',
                            'body',
                        ],
                        'new_end_user' => [
                            'subject',
                            'body',
                        ],
                        'password_reset' => [
                            'subject',
                            'body',
                        ],
                    ],
                    'end_user' => [
                        'email_confirmation' => [
                            'subject',
                            'body',
                        ],
                        'password_reset' => [
                            'subject',
                            'body',
                        ],
                        'contribution_approved' => [
                            'subject',
                            'body',
                        ],
                        'contribution_rejected' => [
                            'subject',
                            'body',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function values_correct_for_index(): void
    {
        $frontendContent = Setting::findOrFail('frontend_content')->value;
        $emailContent = Setting::findOrFail('email_content')->value;

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/settings');

        $response->assertJsonFragment([
            [
                'frontend_content' => [
                    'home_page' => [
                        'title' => $frontendContent['home_page']['title'],
                    ],
                ],
                'email_content' => [
                    'admin' => [
                        'new_contribution' => [
                            'subject' => $emailContent['admin']['new_contribution']['subject'],
                            'body' => $emailContent['admin']['new_contribution']['body'],
                        ],
                        'updated_contribution' => [
                            'subject' => $emailContent['admin']['updated_contribution']['subject'],
                            'body' => $emailContent['admin']['updated_contribution']['body'],
                        ],
                        'new_end_user' => [
                            'subject' => $emailContent['admin']['new_end_user']['subject'],
                            'body' => $emailContent['admin']['new_end_user']['body'],
                        ],
                        'password_reset' => [
                            'subject' => $emailContent['admin']['password_reset']['subject'],
                            'body' => $emailContent['admin']['password_reset']['body'],
                        ],
                    ],
                    'end_user' => [
                        'email_confirmation' => [
                            'subject' => $emailContent['end_user']['email_confirmation']['subject'],
                            'body' => $emailContent['end_user']['email_confirmation']['body'],
                        ],
                        'password_reset' => [
                            'subject' => $emailContent['end_user']['password_reset']['subject'],
                            'body' => $emailContent['end_user']['password_reset']['body'],
                        ],
                        'contribution_approved' => [
                            'subject' => $emailContent['end_user']['contribution_approved']['subject'],
                            'body' => $emailContent['end_user']['contribution_approved']['body'],
                        ],
                        'contribution_rejected' => [
                            'subject' => $emailContent['end_user']['contribution_rejected']['subject'],
                            'body' => $emailContent['end_user']['contribution_rejected']['body'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /*
     * Update.
     */

    /** @test */
    public function guest_cannot_update(): void
    {
        $response = $this->putJson('/v1/settings');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_update(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->putJson('/v1/settings');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_update(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson('/v1/settings', [
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

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_update(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson('/v1/settings', [
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

        $response->assertJsonStructure([
            'data' => [
                'frontend_content' => [
                    'home_page' => [
                        'title',
                    ],
                ],
                'email_content' => [
                    'admin' => [
                        'new_contribution' => [
                            'subject',
                            'body',
                        ],
                        'updated_contribution' => [
                            'subject',
                            'body',
                        ],
                        'new_end_user' => [
                            'subject',
                            'body',
                        ],
                        'password_reset' => [
                            'subject',
                            'body',
                        ],
                    ],
                    'end_user' => [
                        'email_confirmation' => [
                            'subject',
                            'body',
                        ],
                        'password_reset' => [
                            'subject',
                            'body',
                        ],
                        'contribution_approved' => [
                            'subject',
                            'body',
                        ],
                        'contribution_rejected' => [
                            'subject',
                            'body',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function values_correct_for_update(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson('/v1/settings', [
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

        $response->assertJson([
            'data' => [
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
            ],
        ]);
    }
}
