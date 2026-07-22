<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index()
    {
        return view('admin.security.index');
    }

    public function twoFactorSettings()
    {
        return view('admin.security.two-factor');
    }

    public function enableTwoFactor(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|size:6',
        ]);

        // TODO: Verify code and enable 2FA for current user

        return redirect()->route('admin.security.two-factor')->with('success', 'Two-factor authentication enabled successfully.');
    }

    public function disableTwoFactor(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string',
        ]);

        // TODO: Verify password and disable 2FA for current user

        return redirect()->route('admin.security.two-factor')->with('success', 'Two-factor authentication disabled successfully.');
    }

    public function sessions()
    {
        return view('admin.security.sessions');
    }

    public function terminateSession($id)
    {
        // TODO: Terminate session by id

        return redirect()->route('admin.security.sessions')->with('success', 'Session terminated successfully.');
    }

    public function loginHistory()
    {
        return view('admin.security.login-history');
    }

    public function passwordPolicy()
    {
        return view('admin.security.password-policy');
    }

    public function updatePasswordPolicy(Request $request)
    {
        $validated = $request->validate([
            'min_length'          => 'required|integer|min:6|max:128',
            'require_uppercase'   => 'required|boolean',
            'require_lowercase'   => 'required|boolean',
            'require_numbers'     => 'required|boolean',
            'require_special'     => 'required|boolean',
            'password_expiry_days' => 'required|integer|min:0|max:365',
            'history_check'       => 'nullable|boolean',
            'history_count'       => 'nullable|integer|min:0|max:24',
        ]);

        // TODO: Save password policy to database

        return redirect()->route('admin.security.password-policy')->with('success', 'Password policy updated successfully.');
    }

    public function ipRestriction()
    {
        return view('admin.security.ip-restriction');
    }

    public function updateIpRestriction(Request $request)
    {
        $validated = $request->validate([
            'mode'    => 'required|in:whitelist,blacklist',
            'ips'     => 'required|string',
            'enabled' => 'nullable|boolean',
        ]);

        // TODO: Save IP restriction settings to database

        return redirect()->route('admin.security.ip-restriction')->with('success', 'IP restriction settings updated successfully.');
    }
}
