<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\Contribution\TagIdsFilter;
use App\Http\Resources\ContributionResource;
use App\Models\Contribution;
use App\Support\Pagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class ContributionController extends Controller
{
    /**
     * ContributionController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     */
    public function __construct(Request $request, Pagination $pagination)
    {
        parent::__construct($request, $pagination);

        $this->authorizeResource(Contribution::class);
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
            });

        $contributions = QueryBuilder::for($baseQuery)
            ->allowedFilters([
                Filter::exact('end_user_id'),
                Filter::custom('tag_ids', TagIdsFilter::class),
            ])
            ->paginate($this->perPage);

        return ContributionResource::collection($contributions);
    }
}
