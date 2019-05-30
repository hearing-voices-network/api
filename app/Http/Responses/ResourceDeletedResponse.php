<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class ResourceDeletedResponse implements Responsable
{
    /**
     * @var string
     */
    protected $resource;

    /**
     * ResourceDeletedResponse constructor.
     *
     * @param string $resource
     */
    public function __construct(string $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'message' => "The {$this->resource} has been deleted.",
        ]);
    }
}
