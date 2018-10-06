<?php

namespace App\Http\Controllers\Cabinet;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileEditRequest;
use App\Services\User\ProfileService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        return view('cabinet.profile.home', compact('user'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();

        return view('cabinet.profile.edit', compact('user'));
    }

    /**
     * @param ProfileEditRequest $request
     * @param ProfileService $service
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileEditRequest $request, ProfileService $service)
    {
        $service->edit(Auth::user(), $request);

        return redirect()->route('cabinet.profile.home');
    }
}
