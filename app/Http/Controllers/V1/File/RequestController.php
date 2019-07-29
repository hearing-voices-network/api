<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\File;

use App\Events\EndpointInvoked;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileTokenResource;
use App\Models\File;
use App\Models\FileToken;
use App\Services\FileService;
use App\Support\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    /**
     * @var \App\Services\FileService
     */
    protected $fileService;

    /**
     * RequestController constructor.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Support\Pagination $pagination
     * @param \App\Services\FileService $fileService
     */
    public function __construct(Request $request, Pagination $pagination, FileService $fileService)
    {
        parent::__construct($request, $pagination);

        $this->middleware(['auth:api', 'verified']);

        $this->fileService = $fileService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\File $file
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function __invoke(Request $request, File $file): JsonResource
    {
        $this->authorize('request', $file);

        $fileToken = DB::transaction(function () use ($request, $file): FileToken {
            return $this->fileService->request($file, $request->user('api')->admin);
        });

        event(EndpointInvoked::onCreate($request, "Requested file [{$file->id}]."));

        return new FileTokenResource($fileToken);
    }
}
