<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Models\Admin;
use App\Models\Contribution;
use App\Models\EndUser;
use App\Models\Tag;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;
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

    /** @test */
    public function can_sort_by_name_for_index(): void
    {
        $this->truncateTables('tags');

        $tag1 = factory(Tag::class)->create([
            'name' => 'Alpha',
        ]);
        $tag2 = factory(Tag::class)->create([
            'name' => 'Bravo',
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/tags', ['sort' => '-name']);

        $response->assertNthIdInCollection(1, $tag1->id);
        $response->assertNthIdInCollection(0, $tag2->id);
    }

    /*
     * Store.
     */

    /** @test */
    public function guest_cannot_store(): void
    {
        $response = $this->postJson('/v1/tags');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_store(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->postJson('/v1/tags', [
            'parent_tag_id' => null,
            'name' => 'Child tag',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_store(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/tags', [
            'parent_tag_id' => null,
            'name' => 'Child tag',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function structure_correct_for_store(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/tags', [
            'parent_tag_id' => null,
            'name' => 'Child tag',
        ]);

        $response->assertResourceDataStructure([
            'id',
            'parent_tag_id',
            'name',
            'public_contributions',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
    }

    /** @test */
    public function values_correct_for_store(): void
    {
        $now = Date::now();
        Date::setTestNow($now);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/tags', [
            'parent_tag_id' => null,
            'name' => 'Child tag',
        ]);

        $response->assertJsonFragment([
            'parent_tag_id' => null,
            'name' => 'Child tag',
            'public_contributions' => 0,
            'created_at' => $now->toIso8601String(),
            'updated_at' => $now->toIso8601String(),
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function can_have_second_level_tag_for_store(): void
    {
        /** @var \App\Models\Tag $parentTag */
        $parentTag = factory(Tag::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/tags', [
            'parent_tag_id' => $parentTag->id,
            'name' => 'Child tag',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment([
            'parent_tag_id' => $parentTag->id,
            'name' => 'Child tag',
            'public_contributions' => 0,
        ]);
    }

    /** @test */
    public function cannot_have_third_level_tag_for_store(): void
    {
        /** @var \App\Models\Tag $parentTag */
        $parentTag = factory(Tag::class)->create([
            'parent_tag_id' => factory(Tag::class)->create()->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/tags', [
            'parent_tag_id' => $parentTag->id,
            'name' => 'Child tag',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('parent_tag_id');
    }

    /*
     * Show.
     */

    /** @test */
    public function guest_can_show(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->getJson("/v1/tags/{$tag->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function end_user_can_show(): void
    {
        $tag = factory(Tag::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson("/v1/tags/{$tag->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function admin_can_show(): void
    {
        $tag = factory(Tag::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/tags/{$tag->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_show(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->getJson("/v1/tags/{$tag->id}");

        $response->assertJsonStructure([
            'data' => [
                'id',
                'parent_tag_id',
                'name',
                'public_contributions',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
        ]);
    }

    /** @test */
    public function values_correct_for_show(): void
    {
        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        $response = $this->getJson("/v1/tags/{$tag->id}");

        $response->assertJson([
            'data' => [
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
     * Destroy.
     */

    /** @test */
    public function guest_cannot_destroy(): void
    {
        $tag = factory(Tag::class)->create();

        $response = $this->deleteJson("/v1/tags/{$tag->id}", ['type' => 'force_delete']);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_destroy(): void
    {
        $tag = factory(Tag::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->deleteJson("/v1/tags/{$tag->id}", ['type' => 'force_delete']);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function admin_can_destroy(): void
    {
        $tag = factory(Tag::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->deleteJson("/v1/tags/{$tag->id}", ['type' => 'force_delete']);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function database_records_and_relationships_deleted_for_force_destroy(): void
    {
        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)->create();

        $contribution->tags()->sync([$tag->id]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $this->deleteJson("/v1/tags/{$tag->id}", ['type' => 'force_delete']);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
        $this->assertDatabaseMissing('contribution_tag', [
            'contribution_id' => $contribution->id,
            'tag_id' => $tag->id,
        ]);
        $this->assertDatabaseHas('contributions', ['id' => $contribution->id]);
    }

    /** @test */
    public function database_records_and_relationships_not_deleted_for_soft_destroy(): void
    {
        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)->create();

        $contribution->tags()->sync([$tag->id]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $this->deleteJson("/v1/tags/{$tag->id}", ['type' => 'soft_delete']);

        $this->assertSoftDeleted('tags', ['id' => $tag->id]);
        $this->assertDatabaseHas('contribution_tag', [
            'contribution_id' => $contribution->id,
            'tag_id' => $tag->id,
        ]);
        $this->assertDatabaseHas('contributions', ['id' => $contribution->id]);
    }
}
