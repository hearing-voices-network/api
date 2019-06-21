<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Models\Admin;
use App\Models\EndUser;
use App\Models\Tag;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    /*
     * Index.
     */

    /** @test */
    public function guest_can_index(): void
    {
        $response = $this->getJson('/v1/tags');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function end_user_can_index(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson('/v1/tags');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function admin_can_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/tags');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_index(): void
    {
        $response = $this->getJson('/v1/tags');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'parent_tag_id',
                    'name',
                    'public_contributions',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ],
        ]);
    }

    /** @test */
    public function values_correct_for_index(): void
    {
        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        $response = $this->getJson('/v1/tags');

        $response->assertJsonFragment([
            [
                'id' => $tag->id,
                'parent_tag_id' => $tag->parent_tag_id,
                'name' => $tag->name,
                'public_contributions' => $tag->publicContributions()->count(),
                'created_at' => $tag->created_at->toIso8601String(),
                'updated_at' => $tag->updated_at->toIso8601String(),
                'deleted_at' => null,
            ],
        ]);
    }

    /*
     * Store.
     */

    /*
     * Show.
     */

    /*
     * Destroy.
     */
}
