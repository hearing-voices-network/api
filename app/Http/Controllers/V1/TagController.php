<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
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

class TagController extends Controller
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

        $this->middleware('auth')->except('index', 'show');
        $this->authorizeResource(Tag::class);

        $this->tagService = $tagService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $tags = Tag::query()
            ->withCount('publicContributions')
            ->get();

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

        return new TagResource($tag);
    }

    /**
     * @param \App\Models\Tag $tag
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Tag $tag): JsonResource
    {
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

        return new ResourceDeletedResponse('tag');
    }
}
