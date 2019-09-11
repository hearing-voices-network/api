<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Events\EndpointInvoked;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Tag\DestroyTagRequest;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Resources\TagResource;
use App\Http\Responses\ResourceDeletedResponse;
use App\Models\Tag;
use App\Services\TagService;
use App\Support\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class TagController extends ApiController
{
    /**
     * @var \App\Services\TagService
     */
    protected $tagService;

    /**
     * TagController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     * @param \App\Services\TagService $tagService
     */
    public function __construct(Request $request, Pagination $pagination, TagService $tagService)
    {
        parent::__construct($request, $pagination);

        $this->middleware(['auth:api', 'verified'])->except('index', 'show');
        $this->authorizeResource(Tag::class);

        $this->tagService = $tagService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $baseQuery = Tag::query()
            ->withCount('publicContributions');

        $tags = QueryBuilder::for($baseQuery)
            ->allowedSorts([
                'name',
            ])
            ->defaultSort('name')
            ->get();

        event(EndpointInvoked::onRead($request, 'Viewed all tags.'));

        return TagResource::collection($tags);
    }

    /**
     * @param \App\Http\Requests\Tag\StoreTagRequest $request
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store(StoreTagRequest $request): JsonResource
    {
        $tag = $this->tagService->create([
            'parent_tag_id' => $request->parent_tag_id,
            'name' => $request->name,
        ]);

        event(EndpointInvoked::onCreate($request, "Created tag [{$tag->id}]."));

        return new TagResource($tag);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Tag $tag
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Request $request, Tag $tag): JsonResource
    {
        event(EndpointInvoked::onRead($request, "Viewed tag [{$tag->id}]."));

        return new TagResource($tag);
    }

    /**
     * @param \App\Http\Requests\Tag\DestroyTagRequest $request
     * @param \App\Models\Tag $tag
     * @return \App\Http\Responses\ResourceDeletedResponse
     */
    public function destroy(DestroyTagRequest $request, Tag $tag): ResourceDeletedResponse
    {
        DB::transaction(function () use ($request, $tag): void {
            $request->type === DestroyTagRequest::TYPE_FORCE_DELETE
                ? $this->tagService->forceDelete($tag)
                : $this->tagService->softDelete($tag);
        });

        $request->type === DestroyTagRequest::TYPE_FORCE_DELETE
            ? event(EndpointInvoked::onDelete($request, "Force deleted tag [{$tag->id}]."))
            : event(EndpointInvoked::onDelete($request, "Soft deleted tag [{$tag->id}]."));

        return new ResourceDeletedResponse('tag');
    }
}
