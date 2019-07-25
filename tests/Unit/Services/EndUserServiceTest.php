<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Events\EndUser\EndUserCreated;
use App\Events\EndUser\EndUserForceDeleted;
use App\Events\EndUser\EndUserSoftDeleted;
use App\Events\EndUser\EndUserUpdated;
use App\Models\Contribution;
use App\Models\EndUser;
use App\Models\Tag;
use App\Services\EndUserService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EndUserServiceTest extends TestCase
{
    /** @test */
    public function it_creates_a_user_and_end_user_record(): void
    {
        /** @var \App\Services\EndUserService $endUserService */
        $endUserService = resolve(EndUserService::class);

        $endUser = $endUserService->create([
            'email' => 'john.doe@example.com',
            'password' => 'secret',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Mixed Asian/White',
        ]);

        $this->assertDatabaseHas('users', ['id' => $endUser->user_id]);
        $this->assertDatabaseHas('end_users', ['id' => $endUser->id]);
        $this->assertEquals('john.doe@example.com', $endUser->user->email);
        $this->assertTrue(Hash::check('secret', $endUser->user->password));
        $this->assertEquals('United Kingdom', $endUser->country);
        $this->assertEquals(1995, $endUser->birth_year);
        $this->assertEquals('Male', $endUser->gender);
        $this->assertEquals('Mixed Asian/White', $endUser->ethnicity);
    }

    /** @test */
    public function it_dispatches_an_event_when_created(): void
    {
        Event::fake([EndUserCreated::class]);

        /** @var \App\Services\EndUserService $endUserService */
        $endUserService = resolve(EndUserService::class);

        $endUser = $endUserService->create([
            'email' => 'john.doe@example.com',
            'password' => 'secret',
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Mixed Asian/White',
        ]);

        Event::assertDispatched(
            EndUserCreated::class,
            function (EndUserCreated $event) use ($endUser): bool {
                return $event->getEndUser()->is($endUser);
            }
        );
    }

    /** @test */
    public function it_updates_a_user_and_end_user_record(): void
    {
        /** @var \App\Services\EndUserService $endUserService */
        $endUserService = resolve(EndUserService::class);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        $endUser = $endUserService->update($endUser, [
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Mixed Asian/White',
            'email' => 'john.doe@example.com',
            'password' => 'secret',
        ]);

        $this->assertEquals('john.doe@example.com', $endUser->user->email);
        $this->assertTrue(Hash::check('secret', $endUser->user->password));
        $this->assertEquals('United Kingdom', $endUser->country);
        $this->assertEquals(1995, $endUser->birth_year);
        $this->assertEquals('Male', $endUser->gender);
        $this->assertEquals('Mixed Asian/White', $endUser->ethnicity);
    }

    /** @test */
    public function it_dispatches_an_event_when_updated(): void
    {
        Event::fake([EndUserUpdated::class]);

        /** @var \App\Services\EndUserService $endUserService */
        $endUserService = resolve(EndUserService::class);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        $endUser = $endUserService->update($endUser, [
            'country' => 'United Kingdom',
            'birth_year' => 1995,
            'gender' => 'Male',
            'ethnicity' => 'Mixed Asian/White',
            'email' => 'john.doe@example.com',
            'password' => 'secret',
        ]);

        Event::assertDispatched(
            EndUserUpdated::class,
            function (EndUserUpdated $event) use ($endUser): bool {
                return $event->getEndUser()->is($endUser);
            }
        );
    }

    /** @test */
    public function it_soft_deletes_a_user_record(): void
    {
        /** @var \App\Services\EndUserService $endUserService */
        $endUserService = resolve(EndUserService::class);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)->create([
            'end_user_id' => $endUser->id,
        ]);

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        $contribution->tags()->sync([$tag->id]);

        $endUser = $endUserService->softDelete($endUser);

        $this->assertDatabaseHas('end_users', ['id' => $endUser->id]);
        $this->assertDatabaseHas('users', ['id' => $endUser->user->id]);
        $this->assertSoftDeleted('users', ['id' => $endUser->user->id]);
        $this->assertNotNull($endUser->user->deleted_at);
        $this->assertDatabaseHas('contributions', ['id' => $contribution->id]);
        $this->assertDatabaseHas('contribution_tag', [
            'contribution_id' => $contribution->id,
            'tag_id' => $tag->id,
        ]);
    }

    /** @test */
    public function it_dispatches_an_event_when_soft_deleted(): void
    {
        Event::fake([EndUserSoftDeleted::class]);

        /** @var \App\Services\EndUserService $endUserService */
        $endUserService = resolve(EndUserService::class);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        $endUser = $endUserService->softDelete($endUser);

        Event::assertDispatched(
            EndUserSoftDeleted::class,
            function (EndUserSoftDeleted $event) use ($endUser): bool {
                return $event->getEndUser()->is($endUser);
            }
        );
    }

    /** @test */
    public function it_force_deletes_a_user_and_user_user_and_contribution_tag_records(): void
    {
        /** @var \App\Services\EndUserService $endUserService */
        $endUserService = resolve(EndUserService::class);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        /** @var \App\Models\Contribution $contribution */
        $contribution = factory(Contribution::class)->create([
            'end_user_id' => $endUser->id,
        ]);

        /** @var \App\Models\Tag $tag */
        $tag = factory(Tag::class)->create();

        $contribution->tags()->sync([$tag->id]);

        $endUserService->forceDelete($endUser);

        $this->assertDatabaseMissing('end_users', ['id' => $endUser->id]);
        $this->assertDatabaseMissing('users', ['id' => $endUser->user->id]);
        $this->assertDatabaseMissing('contributions', ['id' => $contribution->id]);
        $this->assertDatabaseMissing('contribution_tag', [
            'contribution_id' => $contribution->id,
            'tag_id' => $tag->id,
        ]);
    }

    /** @test */
    public function it_dispatches_an_event_when_force_deleted(): void
    {
        Event::fake([EndUserForceDeleted::class]);

        /** @var \App\Services\EndUserService $endUserService */
        $endUserService = resolve(EndUserService::class);

        /** @var \App\Models\EndUser $endUser */
        $endUser = factory(EndUser::class)->create();

        $endUserService->forceDelete($endUser);

        Event::assertDispatched(
            EndUserForceDeleted::class,
            function (EndUserForceDeleted $event) use ($endUser): bool {
                return $event->getEndUser()->is($endUser);
            }
        );
    }
}
