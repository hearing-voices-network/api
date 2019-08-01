<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Events\EndpointInvoked;
use App\Http\Controllers\Controller;
use App\Http\Filters\Contribution\TagIdsFilter;
use App\Http\Requests\Contribution\StoreContributionRequest;
use App\Http\Requests\Contribution\UpdateContributionRequest;
use App\Http\Resources\ContributionResource;
use App\Http\Responses\ResourceDeletedResponse;
use App\Models\Contribution;
use App\Services\ContributionService;
use App\Support\Pagination;
use Illuminate\Database\Eloquent\Builder;
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

        $this->middleware(['auth:api', 'verified'])->except('index', 'show');
        $this->authorizeResource(Contribution::class);

        $this->contributionService = $contributionService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $isGuest = $request->user('api') === null;
        $isEndUser = optional($request->user('api'))->isEndUser();
        $endUser = optional($request->user('api'))->endUser;

        $baseQuery = Contribution::query()
            ->with('tags.publicContributions')
            ->when($isGuest, function (Builder $query): void {
                // When guest, filter only public.
                $query->where('contributions.status', '=', Contribution::STATUS_PUBLIC);
            })
            ->when($isEndUser, function (Builder $query) use ($endUser): void {
                // When end user, filter only public and all of own.
                $query->where('contributions.status', '=', Contribution::STATUS_PUBLIC)
                    ->orWhere('contributions.end_user_id', '=', $endUser->id);
            });

        $contributions = QueryBuilder::for($baseQuery)
            ->allowedFilters([
                Filter::exact('id'),
                Filter::exact('end_user_id'),
                Filter::custom('tag_ids', TagIdsFilter::class),
            ])
            ->allowedSorts([
                'created_at',
            ])
            ->defaultSort('-created_at')
            ->paginate($this->perPage);

        event(EndpointInvoked::onRead($request, 'Viewed all contributions.'));

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
                'end_user_id' => $request->user('api')->endUser->id,
                'content' => $request->input('content'),
                'status' => $request->status,
                'tags' => $request->input('tags.*.id'),
            ]);
        });

        event(EndpointInvoked::onCreate($request, "Created contribution [{$contribution->id}]."));

        return new ContributionResource($contribution);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contribution $contribution
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Request $request, Contribution $contribution): JsonResource
    {
        event(EndpointInvoked::onRead($request, "Viewed contribution [{$contribution->id}]."));

        return new ContributionResource(
            $contribution->load('tags.publicContributions')
        );
    }

    /**
     * @param \App\Http\Requests\Contribution\UpdateContributionRequest $request
     * @param \App\Models\Contribution $contribution
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function update(
        UpdateContributionRequest $request,
        Contribution $contribution
    ): JsonResource {
        $contribution = DB::transaction(function () use ($request, $contribution): Contribution {
            return $this->contributionService->update($contribution, [
                'content' => $request->input('content'),
                'status' => $request->status,
                'tags' => $request->input('tags.*.id'),
            ]);
        });

        event(EndpointInvoked::onUpdate($request, "Updated contribution [{$contribution->id}]."));

        return new ContributionResource(
            $contribution->load('tags.publicContributions')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contribution $contribution
     * @return \App\Http\Responses\ResourceDeletedResponse
     */
    public function destroy(Request $request, Contribution $contribution): ResourceDeletedResponse
    {
        DB::transaction(function () use ($contribution): void {
            $this->contributionService->delete($contribution);
        });

        event(EndpointInvoked::onDelete($request, "Deleted contribution [{$contribution->id}]."));

        return new ResourceDeletedResponse('contribution');
    }
}
