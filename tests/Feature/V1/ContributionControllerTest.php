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

class ContributionControllerTest extends TestCase
{
    /*
     * Index.
     */

    /** @test */
    public function guest_can_index(): void
    {
        $response = $this->getJson('/v1/contributions');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function end_user_can_index(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson('/v1/contributions');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function admin_can_index(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/contributions');

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_index(): void
    {
        $contribution = factory(Contribution::class)->create();
        $tag = factory(Tag::class)->create();
        $contribution->tags()->attach($tag);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/contributions');

        $response->assertCollectionDataStructure([
            'id',
            'end_user_id',
            'content',
            'excerpt',
            'status',
            'changes_requested',
            'status_last_updated_at',
            'created_at',
            'updated_at',
            'tags' => [
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
        $contribution = factory(Contribution::class)->create();
        $tag = factory(Tag::class)->create();
        $contribution->tags()->attach($tag);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/contributions');

        $response->assertJsonFragment([
            [
                'id' => $contribution->id,
                'end_user_id' => $contribution->endUser->id,
                'content' => $contribution->content,
                'excerpt' => $contribution->getExcerpt(),
                'status' => $contribution->status,
                'changes_requested' => $contribution->changes_requested,
                'status_last_updated_at' => $contribution->status_last_updated_at->toIso8601String(),
                'created_at' => $contribution->created_at->toIso8601String(),
                'updated_at' => $contribution->updated_at->toIso8601String(),
                'tags' => [
                    [
                        'id' => $tag->id,
                        'parent_tag_id' => $tag->parent_tag_id,
                        'name' => $tag->name,
                        'public_contributions' => $tag->publicContributions()->count(),
                        'created_at' => $tag->created_at->toIso8601String(),
                        'updated_at' => $tag->updated_at->toIso8601String(),
                        'deleted_at' => null,
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function can_filter_by_end_user_id_for_index(): void
    {
        $contribution1 = factory(Contribution::class)->create();
        $contribution2 = factory(Contribution::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/contributions', ['filter[end_user_id]' => $contribution1->endUser->id]);

        $response->assertJsonFragment(['id' => $contribution1->id]);
        $response->assertJsonMissing(['id' => $contribution2->id]);
    }

    /** @test */
    public function can_filter_by_tag_ids_for_index(): void
    {
        $contribution1 = factory(Contribution::class)->create();
        $tag1 = factory(Tag::class)->create();
        $contribution1->tags()->attach($tag1);

        $contribution2 = factory(Contribution::class)->create();
        $tag2 = factory(Tag::class)->create();
        $contribution2->tags()->attach($tag2);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/contributions', ['filter[tag_ids]' => $tag1->id]);

        $response->assertJsonFragment(['id' => $contribution1->id]);
        $response->assertJsonMissing(['id' => $contribution2->id]);
    }

    /** @test */
    public function can_filter_by_untagged_for_index(): void
    {
        $contribution1 = factory(Contribution::class)->create();
        $tag1 = factory(Tag::class)->create(['deleted_at' => Date::now()]);
        $contribution1->tags()->attach($tag1);

        $contribution2 = factory(Contribution::class)->create();
        $tag2 = factory(Tag::class)->create();
        $contribution2->tags()->attach($tag2);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/contributions', ['filter[tag_ids]' => 'untagged']);

        $response->assertJsonFragment(['id' => $contribution1->id]);
        $response->assertJsonMissing(['id' => $contribution2->id]);
    }

    /** @test */
    public function can_sort_by_created_at_for_index(): void
    {
        $contribution1 = factory(Contribution::class)->create([
            'created_at' => Date::now(),
        ]);
        $contribution2 = factory(Contribution::class)->create([
            'created_at' => Date::now()->addHour(),
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson('/v1/contributions', ['sort' => '-created_at']);

        $response->assertNthIdInCollection(1, $contribution1->id);
        $response->assertNthIdInCollection(0, $contribution2->id);
    }

    /** @test */
    public function guest_can_only_view_public_for_index(): void
    {
        $publicContribution = factory(Contribution::class)
            ->create();
        $privateContribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PRIVATE)
            ->create();
        $inReviewContribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();
        $changesRequestedContribution = factory(Contribution::class)
            ->state(Contribution::STATUS_CHANGES_REQUESTED)
            ->create();

        $response = $this->getJson('/v1/contributions');

        $response->assertJsonFragment(['id' => $publicContribution->id]);
        $response->assertJsonMissing(['id' => $privateContribution->id]);
        $response->assertJsonMissing(['id' => $inReviewContribution->id]);
        $response->assertJsonMissing(['id' => $changesRequestedContribution->id]);
    }

    /** @test */
    public function end_user_can_only_view_public_and_their_own_for_index(): void
    {
        $endUser = factory(EndUser::class)->create();

        $endUserContribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PRIVATE)
            ->create(['end_user_id' => $endUser->id]);
        $publicContribution = factory(Contribution::class)
            ->create();
        $privateContribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PRIVATE)
            ->create();
        $inReviewContribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();
        $changesRequestedContribution = factory(Contribution::class)
            ->state(Contribution::STATUS_CHANGES_REQUESTED)
            ->create();

        Passport::actingAs($endUser->user);

        $response = $this->getJson('/v1/contributions');

        $response->assertJsonFragment(['id' => $endUserContribution->id]);
        $response->assertJsonFragment(['id' => $publicContribution->id]);
        $response->assertJsonMissing(['id' => $privateContribution->id]);
        $response->assertJsonMissing(['id' => $inReviewContribution->id]);
        $response->assertJsonMissing(['id' => $changesRequestedContribution->id]);
    }

    /*
     * Store.
     */

    /** @test */
    public function guest_cannot_store(): void
    {
        $response = $this->postJson('/v1/contributions');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_can_store(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $tag = factory(Tag::class)->create();

        $response = $this->postJson('/v1/contributions', [
            'content' => 'Lorem ipsum',
            'status' => Contribution::STATUS_PRIVATE,
            'tags' => [
                ['id' => $tag->id],
            ],
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /** @test */
    public function admin_cannot_store(): void
    {
        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->postJson('/v1/contributions');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function structure_correct_for_store(): void
    {
        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $tag = factory(Tag::class)->create();

        $response = $this->postJson('/v1/contributions', [
            'content' => 'Lorem ipsum',
            'status' => Contribution::STATUS_PRIVATE,
            'tags' => [
                ['id' => $tag->id],
            ],
        ]);

        $response->assertResourceDataStructure([
            'id',
            'end_user_id',
            'content',
            'excerpt',
            'status',
            'changes_requested',
            'status_last_updated_at',
            'created_at',
            'updated_at',
            'tags' => [
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
    public function values_correct_for_store(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        Date::setTestNow(Date::now());

        $response = $this->postJson('/v1/contributions', [
            'content' => 'Lorem ipsum',
            'status' => Contribution::STATUS_PRIVATE,
            'tags' => [
                ['id' => $tag->id],
            ],
        ]);

        $response->assertJsonFragment([
            'end_user_id' => $endUser->id,
            'content' => 'Lorem ipsum',
            'excerpt' => 'Lorem ipsum',
            'status' => Contribution::STATUS_PRIVATE,
            'changes_requested' => null,
            'status_last_updated_at' => Date::now()->toIso8601String(),
            'created_at' => Date::now()->toIso8601String(),
            'updated_at' => Date::now()->toIso8601String(),
            'tags' => [
                [
                    'id' => $tag->id,
                    'parent_tag_id' => $tag->parent_tag_id,
                    'name' => $tag->name,
                    'public_contributions' => $tag->publicContributions()->count(),
                    'created_at' => $tag->created_at->toIso8601String(),
                    'updated_at' => $tag->updated_at->toIso8601String(),
                    'deleted_at' => null,
                ],
            ],
        ]);
    }

    /** @test */
    public function content_markdown_is_sanitised_for_store(): void
    {
        $endUser = factory(EndUser::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->postJson('/v1/contributions', [
            'content' => <<<'EOT'
                # This is the heading
                
                <p>This is a HTML paragraph.</p>
                
                This is a standard paragraph.
                
                <script src="https://example.com/xss.js"></script>
                EOT,
            'status' => Contribution::STATUS_PRIVATE,
            'tags' => [],
        ]);

        $response->assertJsonFragment([
            'content' => <<<'EOT'
                # This is the heading
                
                This is a HTML paragraph.
                
                This is a standard paragraph.
                EOT,
        ]);
    }

    /*
     * Show.
     */

    /** @test */
    public function when_public_guest_can_show(): void
    {
        $contribution = factory(Contribution::class)->create();

        $response = $this->getJson("/v1/contributions/{$contribution->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function when_public_end_user_can_show(): void
    {
        $contribution = factory(Contribution::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->getJson("/v1/contributions/{$contribution->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function when_public_admin_can_show(): void
    {
        $contribution = factory(Contribution::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/contributions/{$contribution->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function structure_correct_for_show(): void
    {
        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)->create();
        $contribution->tags()->sync([
            factory(Tag::class)->create()->id,
        ]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/contributions/{$contribution->id}");

        $response->assertResourceDataStructure([
            'id',
            'end_user_id',
            'content',
            'excerpt',
            'status',
            'changes_requested',
            'status_last_updated_at',
            'created_at',
            'updated_at',
            'tags' => [
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
    public function values_correct_for_show(): void
    {
        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)->create();

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        $contribution->tags()->sync([$tag->id]);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->getJson("/v1/contributions/{$contribution->id}");

        $response->assertJsonFragment([
            'id' => $contribution->id,
            'end_user_id' => $contribution->end_user_id,
            'content' => $contribution->content,
            'excerpt' => $contribution->getExcerpt(),
            'status' => $contribution->status,
            'changes_requested' => null,
            'status_last_updated_at' => $contribution->status_last_updated_at->toIso8601String(),
            'created_at' => $contribution->created_at->toIso8601String(),
            'updated_at' => $contribution->updated_at->toIso8601String(),
            'tags' => [
                [
                    'id' => $tag->id,
                    'parent_tag_id' => $tag->parent_tag_id,
                    'name' => $tag->name,
                    'public_contributions' => $tag->publicContributions()->count(),
                    'created_at' => $tag->created_at->toIso8601String(),
                    'updated_at' => $tag->updated_at->toIso8601String(),
                    'deleted_at' => null,
                ],
            ],
        ]);
    }

    /** @test */
    public function guest_cannot_only_view_private_for_show(): void
    {
        $privateContribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PRIVATE)
            ->create();

        $response = $this->getJson("/v1/contributions/{$privateContribution->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function end_user_can_view_their_own_for_show(): void
    {
        $endUser = factory(EndUser::class)->create();

        $endUserPrivateContribution = $privateContribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PRIVATE)
            ->create(['end_user_id' => $endUser->id]);

        Passport::actingAs($endUser->user);

        $response = $this->getJson("/v1/contributions/{$endUserPrivateContribution->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /*
     * Update.
     */

    /** @test */
    public function guest_cannot_update(): void
    {
        $contribution = factory(Contribution::class)->create();

        $response = $this->putJson("/v1/contributions/{$contribution->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_cannot_use_someone_elses_to_update(): void
    {
        $contribution = factory(Contribution::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function end_user_can_use_their_own_to_update(): void
    {
        $endUser = factory(EndUser::class)->create();

        $contribution = factory(Contribution::class)->create([
            'end_user_id' => $endUser->id,
        ]);

        $tag = factory(Tag::class)->create();

        Passport::actingAs($endUser->user);

        $response = $this->putJson("/v1/contributions/{$contribution->id}", [
            'content' => 'Lorem ipsum',
            'status' => Contribution::STATUS_IN_REVIEW,
            'tags' => [
                ['id' => $tag->id],
            ],
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function admin_cannot_update(): void
    {
        $contribution = factory(Contribution::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->putJson("/v1/contributions/{$contribution->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /*
     * Destroy.
     */

    /** @test */
    public function guest_cannot_destroy(): void
    {
        $contribution = factory(Contribution::class)->create();

        $response = $this->deleteJson("/v1/contributions/{$contribution->id}");

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function end_user_using_someone_elses_cannot_destroy(): void
    {
        $contribution = factory(Contribution::class)->create();

        Passport::actingAs(
            factory(EndUser::class)->create()->user
        );

        $response = $this->deleteJson("/v1/contributions/{$contribution->id}");

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function end_user_using_their_own_can_destroy(): void
    {
        $endUser = factory(EndUser::class)->create();

        $contribution = factory(Contribution::class)->create([
            'end_user_id' => $endUser->id,
        ]);

        Passport::actingAs($endUser->user);

        $response = $this->deleteJson("/v1/contributions/{$contribution->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function admin_can_destroy(): void
    {
        $contribution = factory(Contribution::class)->create();

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $response = $this->deleteJson("/v1/contributions/{$contribution->id}");

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function database_records_and_relationships_deleted_for_destroy(): void
    {
        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)->create();

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        $contribution->tags()->sync($tag->id);

        Passport::actingAs(
            factory(Admin::class)->create()->user
        );

        $this->deleteJson("/v1/contributions/{$contribution->id}");

        $this->assertDatabaseMissing('contributions', ['id' => $contribution->id]);
        $this->assertDatabaseMissing('contribution_tag', [
            'contribution_id' => $contribution->id,
            'tag_id' => $tag->id,
        ]);
        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
    }
}
