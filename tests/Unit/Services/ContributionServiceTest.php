<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Contribution;
use App\Services\ContributionService;
use Tests\TestCase;

class ContributionServiceTest extends TestCase
{
    /** @test */
    public function it_changes_status_from_public_to_in_review(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        $contribution = factory(Contribution::class)->create();

        $contribution = $contributionService->update($contribution, []);

        $this->assertEquals(Contribution::STATUS_IN_REVIEW, $contribution->status);
    }

    /** @test */
    public function it_leaves_status_as_private(): void
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
    public function it_leaves_status_as_in_review(): void
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
    public function it_changes_status_from_changes_requested_to_in_review(): void
    {
        /** @var \App\Services\ContributionService $contributionService */
        $contributionService = resolve(ContributionService::class);

        $contribution = factory(Contribution::class)
            ->state(Contribution::STATUS_CHANGES_REQUESTED)
            ->create();

        $contribution = $contributionService->update($contribution, []);

        $this->assertEquals(Contribution::STATUS_IN_REVIEW, $contribution->status);
    }
}
