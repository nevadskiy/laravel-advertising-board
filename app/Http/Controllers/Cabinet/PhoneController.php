<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PhoneVerifyRequest;
use App\Services\User\PhoneService;
use Illuminate\Support\Facades\Auth;

class PhoneController extends Controller
{
    /**
     * @var PhoneService
     */
    private $service;

    /**
     * PhoneController constructor.
     * @param PhoneService $service
     */
    public function __construct(PhoneService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function request()
    {
        try {
            $this->service->request(Auth::user());
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.profile.phone');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form()
    {
        $user = Auth::user();

        return view('cabinet.profile.phone', compact('user'));
    }

    /**
     * @param PhoneVerifyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(PhoneVerifyRequest $request)
    {
        try {
            $this->service->verify(Auth::user(), $request);
        } catch (\DomainException $e) {
            return redirect()->route('cabinet.profile.phone')->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.profile.home');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function auth()
    {
        $this->service->toggleAuth(Auth::user());

        return redirect()->route('cabinet.profile.home');
    }
}
