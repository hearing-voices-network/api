<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Contribution\TagIdsFilter;
use App\Http\Requests\Contribution\StoreContributionRequest;
use App\Http\Resources\ContributionResource;
use App\Models\Contribution;
use App\Services\ContributionService;
use App\Support\Pagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class ContributionController extends Controller
{
    /**
     * @var \App\Services\ContributionService
     */
    protected $contributionService;

    /**
     * ContributionController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     * @param \App\Services\ContributionService $contributionService
     */
    public function __construct(
        Request $request,
        Pagination $pagination,
        ContributionService $contributionService
    ) {
        parent::__construct($request, $pagination);

        $this->middleware('auth')->except('index', 'show');
        $this->authorizeResource(Contribution::class);

        $this->contributionService = $contributionService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $isGuest = $request->user() === null;
        $isEndUser = optional($request->user())->isEndUser();
        $endUser = $isEndUser ? $request->user()->endUser : null;

        $baseQuery = Contribution::query()
            ->with([
                // Append the public_contributions_count.
                'tags' => function (BelongsToMany $query): void {
                    $query->withCount('publicContributions');
                },
            ])
            ->when($isGuest, function (Builder $query): void {
                // When guest, filter only public.
                $query->where('contributions.status', '=', Contribution::STATUS_PUBLIC);
            })
            ->when($isEndUser, function (Builder $query) use ($endUser): void {
                // When end user, filter only public and all of own.
                $query->where('contributions.status', '=', Contribution::STATUS_PUBLIC)
                    ->orWhere('contributions.end_user_id', '=', $endUser->id);
            })
            ->orderByDesc('created_at');

        $contributions = QueryBuilder::for($baseQuery)
            ->allowedFilters([
                Filter::exact('end_user_id'),
                Filter::custom('tag_ids', TagIdsFilter::class),
            ])
            ->paginate($this->perPage);

        return ContributionResource::collection($contributions);
    }

    /**
     * @param \App\Http\Requests\Contribution\StoreContributionRequest $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(StoreContributionRequest $request): JsonResource
    {
        $contribution = DB::transaction(function () use ($request): Contribution {
            return $this->contributionService->create([
                'end_user_id' => $request->user()->endUser->id,
                'content' => $request->input('content'),
                'status' => $request->status,
                'tags' => $request->input('tags.*.id'),
            ]);
        });

        return new ContributionResource($contribution);
    }
}
