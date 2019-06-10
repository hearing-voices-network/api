<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\AdminService;
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
    }

    /** @test */
    public function it_throws_exception_when_needed_values_are_not_provided()
    {
        $this->expectException(\ErrorException::class);

        /** @var \App\Services\AdminService $adminService */
        $adminService = resolve(AdminService::class);

        $adminService->create([]);
    }
}
