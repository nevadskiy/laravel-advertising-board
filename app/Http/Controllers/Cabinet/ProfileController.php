<?php

namespace App\Http\Controllers\Cabinet;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        return view('cabinet.profile.home', ['user' => Auth::user()]);
    }

    public function edit()
    {
        return view('cabinet.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|regex:/^\d+$/s',
        ]);

        $user = Auth::user();

        $oldPhone = $user->phone;

        $user->update($request->only(['name', 'last_name', 'phone']));

        if ($user->phone !== $oldPhone) {
            $user->unverifyPhone();
        }

        return redirect()->route('cabinet.profile.home');
    }
}
