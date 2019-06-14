<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Models\Setting;
use App\Services\SettingService;
use App\Support\Pagination;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * @var \App\Services\SettingService
     */
    protected $settingService;

    /**
     * SettingController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     * @param \App\Services\SettingService $settingService
     */
    public function __construct(
        Request $request,
        Pagination $pagination,
        SettingService $settingService
    ) {
        parent::__construct($request, $pagination);

        $this->middleware('auth')->except('index');

        $this->settingService = $settingService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('list', Setting::class);

        return Setting::toResponse(
            optional($request->user())->isAdmin() ?? false
        );
    }

    /**
     * @param \App\Http\Requests\Setting\UpdateSettingRequest $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSettingRequest $request): JsonResponse
    {
        $this->authorize('update', Setting::class);

        DB::transaction(function () use ($request): void {
            $this->settingService->update([
                'frontend_content' => [
                    'home_page' => [
                        'title' => $request->input('frontend_content.home_page.title'),
                    ],
                ],
                'email_content' => [
                    'admin' => [
                        'new_contribution' => [
                            'subject' => $request->input('email_content.admin.new_contribution.subject'),
                            'body' => $request->input('email_content.admin.new_contribution.body'),
                        ],
                        'updated_contribution' => [
                            'subject' => $request->input('email_content.admin.updated_contribution.subject'),
                            'body' => $request->input('email_content.admin.updated_contribution.body'),
                        ],
                        'new_end_user' => [
                            'subject' => $request->input('email_content.admin.new_end_user.subject'),
                            'body' => $request->input('email_content.admin.new_end_user.body'),
                        ],
                        'password_reset' => [
                            'subject' => $request->input('email_content.admin.password_reset.subject'),
                            'body' => $request->input('email_content.admin.password_reset.body'),
                        ],
                    ],
                    'end_user' => [
                        'email_confirmation' => [
                            'subject' => $request->input('email_content.end_user.email_confirmation.subject'),
                            'body' => $request->input('email_content.end_user.email_confirmation.body'),
                        ],
                        'password_reset' => [
                            'subject' => $request->input('email_content.end_user.password_reset.subject'),
                            'body' => $request->input('email_content.end_user.password_reset.body'),
                        ],
                        'contribution_approved' => [
                            'subject' => $request->input('email_content.end_user.contribution_approved.subject'),
                            'body' => $request->input('email_content.end_user.contribution_approved.body'),
                        ],
                        'contribution_rejected' => [
                            'subject' => $request->input('email_content.end_user.contribution_rejected.subject'),
                            'body' => $request->input('email_content.end_user.contribution_rejected.body'),
                        ],
                    ],
                ],
            ]);
        });

        return Setting::toResponse(Setting::WITH_PRIVATE);
    }
}
