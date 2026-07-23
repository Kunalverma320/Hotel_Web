<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->roles()->exists()) {
            return $next($request);
        }

        // If not admin, redirect to public homepage with error notice
        return redirect()->to('/')->with('error', 'Access Denied: You do not have permissions to access the administrative dashboard.');
    }
}
