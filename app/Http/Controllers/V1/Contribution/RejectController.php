<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Contribution;

use App\Events\EndpointInvoked;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contribution\RejectContributionRequest;
use App\Http\Resources\ContributionResource;
use App\Models\Contribution;
use App\Services\ContributionService;
use App\Support\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RejectController extends Controller
{
    /**
     * RejectController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     */
    public function __construct(Request $request, Pagination $pagination)
    {
        parent::__construct($request, $pagination);

        $this->middleware(['auth', 'verified']);
    }

    /**
     * @param \App\Http\Requests\Contribution\RejectContributionRequest $request
     * @param \App\Services\ContributionService $contributionService
     * @param \App\Models\Contribution $contribution
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function __invoke(
        RejectContributionRequest $request,
        ContributionService $contributionService,
        Contribution $contribution
    ): JsonResource {
        $this->authorize('reject', $contribution);

        $contribution = DB::transaction(
            function () use ($request, $contributionService, $contribution): Contribution {
                return $contributionService->reject($contribution, $request->changes_requested);
            }
        );

        event(EndpointInvoked::onUpdate($request, "Rejected contribution [{$contribution->id}]."));

        return new ContributionResource($contribution);
    }
}
