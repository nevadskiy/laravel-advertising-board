<?php

namespace App\Services\User;

use App\Entity\User\User;
use App\Http\Requests\Auth\PhoneVerifyRequest;
use App\Services\Sms\SmsSender;
use Illuminate\Support\Carbon;

class PhoneService
{
    private $sms;

    public function __construct(SmsSender $sms)
    {
        $this->sms = $sms;
    }

    public function request(User $user)
    {
        $token = $user->requestPhoneVerification(Carbon::now());
        $this->sms->send($user->phone, 'Phone verification token: ' . $token);
    }

    public function verify(User $user, PhoneVerifyRequest $request)
    {
        $user->verifyPhone($request['token'], Carbon::now());
    }

    public function toggleAuth(User $user): bool
    {
        if ($user->isPhoneAuthEnabled()) {
            $user->disablePhoneAuth();
        } else {
            $user->enablePhoneAuth();
        }

        return $user->isPhoneAuthEnabled();
    }
}
