<?php

namespace Tests\Unit\Entity\User;

use App\Entity\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_register_from_request()
    {
        $user = User::register(
            $name = 'name',
            $email = 'email',
            $password = 'password'
        );

        $this->assertNotEmpty($user);

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);

        $this->assertNotEmpty($user->password);
        $this->assertNotEquals($password, $user->password);

        $this->assertTrue($user->isWait());
        $this->assertFalse($user->isActive());
    }

    /** @test */
    public function it_can_verify()
    {
        $user = User::register('name', 'email', 'password');

        $user->verify();

        $this->assertFalse($user->isWait());
        $this->assertTrue($user->isActive());
    }

    /** @test */
    public function it_expects_already_verified()
    {
        $user = User::register('name1', 'email', 'password');

        $user->verify();

        $this->expectExceptionMessage('User is already verified.');

        $user->verify();
    }
}
