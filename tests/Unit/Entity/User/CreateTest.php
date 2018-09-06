<?php

namespace Tests\Unit\Entity\User;

use App\Entity\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_from_admin()
    {
        $user = User::generate(
            $name = 'name',
            $email = 'email'
        );

        $this->assertNotEmpty($user);

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $this->assertNotEmpty($user->password);

        $this->assertTrue($user->isActive());
    }
}
