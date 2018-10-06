<?php

namespace App\Services\Auth;

use Laravel\Socialite\Contracts\User as NetworkUser;
use App\Entity\User\User;
use Illuminate\Auth\Events\Registered;

class NetworkService
{
    public function auth(string $network, NetworkUser $data): User
    {
        if ($user = User::byNetwork($network, $data->getId())->first()) {
            return $user;
        }

        // Check if returned email is already exists in database
        if ($data->getEmail() && $user = User::where('email', $data->getEmail())->exists()) {
            throw new \DomainException('User with this email is already registered');
        }

        $user = DB::transaction(function () use ($network, $data) {
            return User::registerByNetwork($network, $data->getId());
        });

        event(new Registered($user));

        return $user;
    }

    public function attach(User $user, string $network, NetworkUser $data): void
    {
        if (User::byNetwork($network, $data->getId())->where('id', '<>', $user->id)->first()) {
            // Or merge accounts (replace his user_id relations with given user_id)
            throw new \DomainException('This network is already attached to another user');
        }

        if (User::byNetwork($network, $data->getId())->first()) {
            throw new \DomainException('User with this email is already registered');
        }

       $user->attachNetwork($network, $data->getId());
    }
}