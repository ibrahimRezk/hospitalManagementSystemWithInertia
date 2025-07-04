<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // return $request->wantsJson()
        //             ? response()->json(['two_factor' => false])
        //             // : redirect()->intended(request()->is(['admin', 'admin/*']) ? 'admin/dashboard' : '/dashboard');
        //             : redirect()->intended('admin/dashboard');


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

        // if (auth()->user()->hasRole('Editor')) {
        //     return redirect()->route('admin.dashboard');
        // }
    }
}
