<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (! Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();

            return redirect()->route('login')->with('error', 'Akun Anda sedang nonaktif.');
        }

        return $next($request);
    }
}
