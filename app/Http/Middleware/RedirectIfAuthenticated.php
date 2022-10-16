<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // return redirect(RouteServiceProvider::HOME);
                if (auth()->user()->hasRole('Super Admin')) {
                    return redirect()->route('admin.dashboard');
                }
                if (auth()->user()->hasRole('Doctor')) {
                    return redirect()->route('doctor.dashboard');
                }
                if (auth()->user()->hasRole('Radiologist')) {
                    return redirect()->route('radiologist.dashboard');
                }
                if (auth()->user()->hasRole('Laboratorist')) {
                    return redirect()->route('laboratorist.dashboard');
                }
                if (auth()->user()->hasRole('Patient')) {
                    return redirect()->route('patient.dashboard');
                }
            }
        }

        return $next($request); 
    }
}
