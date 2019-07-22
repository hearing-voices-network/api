<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Admin;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminServiceTest extends TestCase
{
    /** @test */
    public function it_creates_a_user_and_admin_record(): void
    {
        /** @var \App\Services\AdminService $adminService */
        $adminService = resolve(AdminService::class);

        $admin = $adminService->create([
            'name' => 'John',
            'email' => 'john.doe@example.com',
            'phone' => '07700000000',
            'password' => 'secret',
        ]);

        $this->assertDatabaseHas('admins', ['id' => $admin->id]);
        $this->assertDatabaseHas('users', ['id' => $admin->user_id]);
        $this->assertEquals('John', $admin->name);
        $this->assertEquals('john.doe@example.com', $admin->user->email);
        $this->assertEquals('07700000000', $admin->phone);
        $this->assertTrue(Hash::check('secret', $admin->user->password));
    }

    /** @test */
    public function it_throws_exception_when_needed_values_for_creation_are_not_provided(): void
    {
        $this->expectException(\ErrorException::class);

        /** @var \App\Services\AdminService $adminService */
        $adminService = resolve(AdminService::class);

        $adminService->create([]);
    }

    /** @test */
    public function it_updates_a_user_and_admin_record(): void
    {
        /** @var \App\Services\AdminService $adminService */
        $adminService = resolve(AdminService::class);

        $admin = Admin::create([
            'name' => 'John Doe',
            'phone' => '07700000000',
            'user_id' => User::create([
                'email' => 'john.doe@example.com',
                'password' => Hash::make('secret'),
                'email_verified_at' => Date::now(),
            ])->id,
        ]);

        $adminService->update($admin, [
            'name' => 'Foo Bar',
            'phone' => '07777777777',
            'email' => 'foo.bar@example.com',
            'password' => 'password',
        ]);

        $this->assertEquals('Foo Bar', $admin->name);
        $this->assertEquals('foo.bar@example.com', $admin->user->email);
        $this->assertEquals('07777777777', $admin->phone);
        $this->assertTrue(Hash::check('password', $admin->user->password));
    }

    /** @test */
    public function it_deletes_a_user_and_admin_record(): void
    {
        /** @var \App\Services\AdminService $adminService */
        $adminService = resolve(AdminService::class);

        $admin = Admin::create([
            'name' => 'John Doe',
            'phone' => '07700000000',
            'user_id' => User::create([
                'email' => 'john.doe@example.com',
                'password' => Hash::make('secret'),
                'email_verified_at' => Date::now(),
            ])->id,
        ]);

        $adminService->delete($admin);

        $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
        $this->assertDatabaseMissing('users', ['id' => $admin->user_id]);
    }
}
