<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCashier
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('cashier.login');
        }

        if (! Auth::user()?->isCashier()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('cashier.login')
                ->withErrors([
                    'username' => 'Akun ini tidak memiliki akses kasir.',
                ]);
        }

        return $next($request);
    }
}
