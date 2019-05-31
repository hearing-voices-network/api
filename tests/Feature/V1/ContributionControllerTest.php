<?php

declare(strict_types=1);

namespace Tests\Feature\V1;

use App\Models\Admin;
use App\Models\Contribution;
use App\Models\EndUser;
use App\Models\Tag;
use Illuminate\Http\Response;
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
                [
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
        $tag1 = factory(Tag::class)->create(['deleted_at' => now()]);
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
    public function guest_can_only_view_public_for_index()
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
    public function end_user_can_only_view_public_and_their_own_for_index()
    {
        $endUser = factory(EndUser::class)->create();

        $endUserContribution = $privateContribution = factory(Contribution::class)
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
}
