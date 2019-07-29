<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Events\Contribution\ContributionApproved;
use App\Events\Contribution\ContributionCreated;
use App\Events\Contribution\ContributionDeleted;
use App\Events\Contribution\ContributionRejected;
use App\Events\Contribution\ContributionUpdated;
use App\Models\Contribution;
use App\Models\EndUser;
use App\Models\Tag;
use App\Services\ContributionService;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ContributionServiceTest extends TestCase
{
    /** @test */
    public function it_creates_a_contribution_tag_and_contribution_record(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        $contribution = $contributionService->create([
            'end_user_id' => $endUser->id,
            'content' => 'Lorem ipsum',
            'status' => Contribution::STATUS_PRIVATE,
            'tags' => [$tag->id],
        ]);

        $this->assertDatabaseHas('contributions', ['id' => $contribution->id]);
        $this->assertDatabaseHas('contribution_tag', [
            'contribution_id' => $contribution->id,
            'tag_id' => $tag->id,
        ]);
        $this->assertEquals($endUser->id, $contribution->end_user_id);
        $this->assertEquals('Lorem ipsum', $contribution->content);
        $this->assertEquals(Contribution::STATUS_PRIVATE, $contribution->status);
        $this->assertEquals([$tag->id], $contribution->tags->pluck('id')->toArray());
    }

    /** @test */
    public function it_dispatches_an_event_when_created(): void
    {
        Event::fake([ContributionCreated::class]);

        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        $contribution = $contributionService->create([
            'end_user_id' => $endUser->id,
            'content' => 'Lorem ipsum',
            'status' => Contribution::STATUS_PRIVATE,
            'tags' => [],
        ]);

        Event::assertDispatched(
            ContributionCreated::class,
            function (ContributionCreated $event) use ($contribution): bool {
                return $event->getContribution()->is($contribution);
            }
        );
    }

    /** @test */
    public function it_updates_and_changes_status_from_public_to_in_review(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        $contribution = factory(Contribution::class)->create();

        $contribution = $contributionService->update($contribution, []);

        $this->assertEquals(Contribution::STATUS_IN_REVIEW, $contribution->status);
    }

    /** @test */
    public function it_updates_and_leaves_status_as_private(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_PRIVATE)
            ->create();

        $contribution = $contributionService->update($contribution, []);

        $this->assertEquals(Contribution::STATUS_PRIVATE, $contribution->status);
    }

    /** @test */
    public function it_updates_and_leaves_status_as_in_review(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();

        $contribution = $contributionService->update($contribution, []);

        $this->assertEquals(Contribution::STATUS_IN_REVIEW, $contribution->status);
    }

    /** @test */
    public function it_updates_and_changes_status_from_changes_requested_to_in_review(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_CHANGES_REQUESTED)
            ->create();

        $contribution = $contributionService->update($contribution, []);

        $this->assertEquals(Contribution::STATUS_IN_REVIEW, $contribution->status);
    }

    /** @test */
    public function it_updates_tags(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();

        $tag1 = factory(Tag::class)->create();
        $tag2 = factory(Tag::class)->create();

        $contribution->tags()->sync([$tag1->id]);

        $contribution = $contributionService->update($contribution, [
            'tags' => [$tag2->id],
        ]);

        $this->assertDatabaseMissing('contribution_tag', [
            'contribution_id' => $contribution->id,
            'tag_id' => $tag1->id,
        ]);
        $this->assertDatabaseHas('contribution_tag', [
            'contribution_id' => $contribution->id,
            'tag_id' => $tag2->id,
        ]);
    }

    /** @test */
    public function it_dispatches_an_event_when_updated(): void
    {
        Event::fake([ContributionUpdated::class]);

        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        $contribution = factory(Contribution::class)->create();

        $contribution = $contributionService->update($contribution, []);

        Event::assertDispatched(
            ContributionUpdated::class,
            function (ContributionUpdated $event) use ($contribution): bool {
                return $event->getContribution()->is($contribution);
            }
        );
    }

    /** @test */
    public function it_deletes_the_contribution_tag_and_contribution_records(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)->create();

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        $contribution->tags()->sync([$tag->id]);

        $contributionService->delete($contribution);

        $this->assertDatabaseMissing('contributions', ['id' => $contribution->id]);
        $this->assertDatabaseMissing('contribution_tag', [
            'contribution_id' => $contribution->id,
            'tag_id' => $tag->id,
        ]);
    }

    /** @test */
    public function it_dispatches_an_event_when_deleted(): void
    {
        Event::fake([ContributionDeleted::class]);

        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)->create();

        $contributionService->delete($contribution);

        Event::assertDispatched(
            ContributionDeleted::class,
            function (ContributionDeleted $event) use ($contribution): bool {
                return $event->getContribution()->is($contribution);
            }
        );
    }

    /** @test */
    public function it_approves_a_contribution(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_CHANGES_REQUESTED)
            ->create();

        $now = Date::now()->addMonth();
        Date::setTestNow($now);

        $contribution = $contributionService->approve($contribution);

        $this->assertEquals(Contribution::STATUS_PUBLIC, $contribution->status);
        $this->assertEquals(null, $contribution->changes_requested);
        $this->assertEquals(
            $now->toIso8601String(),
            $contribution->status_last_updated_at->toIso8601String()
        );
    }

    /** @test */
    public function it_dispatches_an_event_when_approved(): void
    {
        Event::fake([ContributionApproved::class]);

        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_CHANGES_REQUESTED)
            ->create();

        $contribution = $contributionService->approve($contribution);

        Event::assertDispatched(
            ContributionApproved::class,
            function (ContributionApproved $event) use ($contribution): bool {
                return $event->getContribution()->is($contribution);
            }
        );
    }

    /** @test */
    public function it_rejects_a_contribution(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();

        $now = Date::now()->addMonth();
        Date::setTestNow($now);

        $contribution = $contributionService->reject($contribution, 'Lorem ipsum');

        $this->assertEquals(Contribution::STATUS_CHANGES_REQUESTED, $contribution->status);
        $this->assertEquals('Lorem ipsum', $contribution->changes_requested);
        $this->assertEquals(
            $now->toIso8601String(),
            $contribution->status_last_updated_at->toIso8601String()
        );
    }

    /** @test */
    public function it_dispatches_an_event_when_rejected(): void
    {
        Event::fake([ContributionRejected::class]);

        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_IN_REVIEW)
            ->create();

        $contribution = $contributionService->reject($contribution, 'Lorem ipsum');

        Event::assertDispatched(
            ContributionRejected::class,
            function (ContributionRejected $event) use ($contribution): bool {
                return $event->getContribution()->is($contribution);
            }
        );
    }
}
