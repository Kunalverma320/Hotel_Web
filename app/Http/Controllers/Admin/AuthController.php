<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'hotel_id' => 'nullable|exists:hotels,id',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        $user = Auth::user();

        if ($user->two_factor_secret) {
            session([
                '2fa_pending_user' => $user->id,
                '2fa_selected_hotel_id' => $request->input('hotel_id')
            ]);
            Auth::logout();
            return redirect()->route('login.2fa.form');
        }

        return $this->finalizeLogin($user, $request->input('hotel_id'));
    }

    public function showTwoFactorForm()
    {
        if (!session('2fa_pending_user')) {
            return redirect()->route('login');
        }
        return view('admin.auth.two-factor');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate(['otp_code' => 'required|string|size:6']);

        $user = User::find(session('2fa_pending_user'));
        if (!$user || $user->two_factor_secret !== $request->otp_code) {
            throw ValidationException::withMessages([
                'otp_code' => ['The provided code is invalid.'],
            ]);
        }

        $selectedHotelId = session('2fa_selected_hotel_id');
        session()->forget(['2fa_pending_user', '2fa_selected_hotel_id']);
        Auth::login($user);

        return $this->finalizeLogin($user, $selectedHotelId);
    }

    public function verifyBackupCode(Request $request)
    {
        $request->validate(['backup_code' => 'required|string']);

        $user = User::find(session('2fa_pending_user'));
        if (!$user) {
            return redirect()->route('login');
        }

        $selectedHotelId = session('2fa_selected_hotel_id');
        session()->forget(['2fa_pending_user', '2fa_selected_hotel_id']);
        Auth::login($user);

        return $this->finalizeLogin($user, $selectedHotelId);
    }

    protected function finalizeLogin($user, $selectedHotelId = null)
    {
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);

        if ($selectedHotelId) {
            session(['current_hotel_id' => $selectedHotelId]);
        } elseif ($user->hotel_id) {
            session(['current_hotel_id' => $user->hotel_id]);
        } else {
            $firstHotel = \App\Models\Hotel::first();
            if ($firstHotel) {
                session(['current_hotel_id' => $firstHotel->id]);
            }
        }

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties(['ip' => request()->ip(), 'user_agent' => request()->userAgent()])
            ->event('login')
            ->log('User logged in');

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            activity()
                ->performedOn($user)
                ->causedBy($user)
                ->withProperties(['ip' => $request->ip()])
                ->event('logout')
                ->log('User logged out');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = \Illuminate\Support\Facades\Password::sendResetLink(
            $request->only('email')
        );

        return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => \Illuminate\Support\Str::random(60),
                ])->save();
            }
        );

        return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function showResetForm($token)
    {
        return view('admin.auth.reset-password', ['token' => $token]);
    }
}
