<?php

namespace App\Docs;

use GoldSpecDigital\ObjectOrientedOAS\Objects\{Info, MediaType, Operation, PathItem, Paths, Response, Schema};
use GoldSpecDigital\ObjectOrientedOAS\OpenApi;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class OpenApiBuilder implements Responsable
{
    /**
     * @var \GoldSpecDigital\ObjectOrientedOAS\OpenApi
     */
    protected $openApi;

    /**
     * OpenApiBuilder constructor.
     */
    public function __construct()
    {
        $this->openApi = OpenApi::create()
            ->version(OpenApi::VERSION_3_0_1)
            ->info(
                Info::create()
                    ->title('Hearing Voices Network API')
                    ->version('v1')
            )
            ->paths(
                Paths::create(
                    PathItem::create()
                        ->route('/users')
                        ->operations(
                            Operation::get(
                                Response::create()
                                    ->statusCode(200)
                                    ->description('OK')
                                    ->content(
                                        MediaType::json(
                                            Schema::string()
                                                ->example('Test string')
                                        )
                                    )
                            )
                        )
                )
            );
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json($this->openApi->toArray());
    }
}
