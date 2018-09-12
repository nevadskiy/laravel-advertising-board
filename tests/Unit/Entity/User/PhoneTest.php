<?php

namespace Tests\Unit\Entity\User;

use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PhoneTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_with_no_verified_phone()
    {
        $user = factory(User::class)->create([
            'phone' => null,
            'phone_verified' => false,
            'phone_verify_token' => null
        ]);

        $this->assertFalse($user->isPhoneVerified());
    }

    /** @test */
    public function it_does_not_allow_empty_phone()
    {
        $user = factory(User::class)->create([
            'phone' => null,
            'phone_verified' => false,
            'phone_verify_token' => null,
        ]);

        $this->expectExceptionMessage('Phone number is empty');
        $user->requestPhoneVerification(Carbon::now());
    }

    /** @test */
    public function it_creates_request_for_verification()
    {
        $user = factory(User::class)->create([
            'phone' => '380000000000',
            'phone_verified' => false,
            'phone_verify_token' => null,
        ]);

        $token = $user->requestPhoneVerification(Carbon::now());

        $this->assertFalse($user->isPhoneVerified());
        $this->assertNotEmpty($token);
    }

    /** @test */
    public function it_creates_request_for_verification_with_old_phone()
    {
        $user = factory(User::class)->create([
            'phone' => '380000000000',
            'phone_verified' => true,
            'phone_verify_token' => null,
            'phone_verify_token_expire' => null,
        ]);

        $this->assertTrue($user->isPhoneVerified());

        // TODO: pass a phone to this method
        $user->requestPhoneVerification(Carbon::now());

        $this->assertFalse($user->isPhoneVerified());
        $this->assertNotEmpty($user->phone_verify_token);
    }

    /** @test */
    public function it_creates_new_verification_after_timeout()
    {
        $user = factory(User::class)->create([
            'phone' => '380000000000',
            'phone_verified' => true,
            'phone_verify_token' => null,
            'phone_verify_token_expire' => null,
        ]);

        $this->assertTrue($user->isPhoneVerified());

        $token1 = $user->requestPhoneVerification($time = Carbon::now());
        $token2 = $user->requestPhoneVerification($time->copy()->addSeconds(500));

        $this->assertFalse($user->isPhoneVerified());
        $this->assertNotEquals($token1, $token2);
    }

    /** @test */
    public function it_sent_already_requested_warning()
    {
        $user = factory(User::class)->create([
            'phone' => '380000000000',
            'phone_verified' => true,
            'phone_verify_token' => null,
        ]);

        $user->requestPhoneVerification($time = Carbon::now());

        $this->expectExceptionMessage('Token is already requested');

        $user->requestPhoneVerification($time->copy()->addSeconds(15));
    }

    /** @test */
    public function it_verifies_phone()
    {
        $user = factory(User::class)->create([
            'phone' => '380000000000',
            'phone_verified' => false,
            'phone_verify_token' => $token = 'token',
            'phone_verify_token_expire' => $time = Carbon::now(),
        ]);

        $this->assertFalse($user->isPhoneVerified());

        $user->verifyPhone($token, $time->copy()->subSeconds(15));

        $this->assertTrue($user->isPhoneVerified());
    }

    /** @test */
    public function it_does_not_verify_phone_with_incorrect_token()
    {
        $user = factory(User::class)->create([
            'phone' => '380000000000',
            'phone_verified' => false,
            'phone_verify_token' => 'token',
            'phone_verify_token_expire' => $time = Carbon::now(),
        ]);

        $this->expectExceptionMessage('Incorrect verify token');

        $user->verifyPhone('incorrect_token', $time->copy()->subSeconds(15));
    }

    /** @test */
    public function it_does_not_verify_phone_with_expired_token()
    {
        $user = factory(User::class)->create([
            'phone' => '380000000000',
            'phone_verified' => false,
            'phone_verify_token' => $token = 'token',
            'phone_verify_token_expire' => $time = Carbon::now(),
        ]);

        $this->expectExceptionMessage('Token is expired');

        $user->verifyPhone('token', $time->copy()->addSeconds(15));
    }
}
