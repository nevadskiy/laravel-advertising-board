<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\Sms\SmsSender;
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Controllers\Controller;
use App\Entity\User\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * @var SmsSender
     */
    private $sms;

    public function __construct(SmsSender $sms)
    {
        $this->middleware('guest')->except('logout');
        $this->sms = $sms;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $authenticate = Auth::attempt(
            $request->only(['email', 'password']),
            $request->filled('remember')
        );

        if ($authenticate) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            $user = Auth::user();

            if ($user->status !== User::STATUS_ACTIVE) {
                Auth::guard()->logout();
                return back()->with('error', 'You need to confirm your account. Please check your email');
            }

            if ($user->isPhoneAuthEnabled()) {
                Auth::logout();
                $token = (string) random_int(10000, 99999);
                $request->session()->put('auth', [
                    'id' => $user->id,
                    'token' => $token,
                    'remember' => $request->filled('remember')
                ]);
                $this->sms->send($user->phone, 'Login code: ' . $token);

                return redirect()->route('login.phone');
            }

            return redirect()->intended(route('cabinet.home'));
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function phone()
    {
        return view('auth.phone');
    }

    public function verify(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->sendLockoutResponse($request);
        }

        $this->validate($request, [
            'token' => 'required|string'
        ]);

        if (!$session = $request->session()->get('auth')) {
            throw new BadRequestHttpException('Missing token info');
        }
        $user = User::findOrFail($session['id']);

        if ($request['token'] === $session['token']) {
            $request->session()->flush();
            $this->clearLoginAttempts($request);
            Auth::login($user, $session['remember']);

            return redirect()->intended(route('cabinet.home'));
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages(['token' => ['Invalid auth token']]);
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return redirect()->route('home');
    }

    protected function username()
    {
        return 'email';
    }
}
