<?php

namespace Tests\Unit\Entity\User;

use App\Entity\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_change_role()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_USER]);

        $this->assertFalse($user->isAdmin());

        $user->changeRole(User::ROLE_ADMIN);

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function it_except_the_same_role_during_changing()
    {
        $user = factory(User::class)->create(['role' => User::ROLE_ADMIN]);

        $this->expectExceptionMessage('Role is already assigned.');

        $user->changeRole(User::ROLE_ADMIN);
    }
}
