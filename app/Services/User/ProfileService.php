<?php

namespace App\Services\User;

use App\Entity\User\User;
use App\Http\Requests\User\ProfileEditRequest;

class ProfileService
{
    public function edit(User $user, ProfileEditRequest $request): void
    {
        $oldPhone = $user->phone;

        $user->update($request->only('name', 'last_name', 'phone'));

        if ($user->phone !== $oldPhone) {
            $user->unverifyPhone();
        }
    }
}
