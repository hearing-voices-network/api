<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Contribution;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContributionResource;
use App\Models\Contribution;
use App\Services\ContributionService;
use App\Support\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ApproveController extends Controller
{
    /**
     * ApproveController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     */
    public function __construct(Request $request, Pagination $pagination)
    {
        parent::__construct($request, $pagination);

        $this->middleware('auth');
    }

    /**
     * @param \App\Services\ContributionService $contributionService
     * @param \App\Models\Contribution $contribution
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function __invoke(
        ContributionService $contributionService,
        Contribution $contribution
    ): JsonResource {
        $this->authorize('approve', $contribution);

        $contribution = DB::transaction(
            function () use ($contributionService, $contribution): Contribution {
                return $contributionService->approve($contribution);
            }
        );

        return new ContributionResource($contribution);
    }
}
