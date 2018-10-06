<?php

namespace App\Http\Controllers\Auth;

use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Services\Auth\NetworkService;
use DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class NetworkController extends Controller
{
    private $service;

    public function __construct(NetworkService $service)
    {
        $this->service = $service;
    }

    public function redirect(string $network)
    {
        return Socialite::driver($network)->redirect();
    }

    public function callback(string $network)
    {
        $data = Socialite::driver($network)->user();
        // dd($data);

        // Find in system by ID and Driver and login him
        // OR create the new user with received credentials
        // ALSO: Check if returned email is already exists in database
        // THEN: throw an error

        try {
            if (Auth::check()) {
                $this->service->attach(Auth::user(), $network, $data);
            } else {
                $user = $this->service->auth($network, $data);
                Auth::login($user);
            }

            return redirect()->intended();
        } catch (\DomainException $e) {
            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }
}
