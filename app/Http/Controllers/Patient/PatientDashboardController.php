<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd($request);
        return Inertia::render('Patient/Dashboard');
    }
}
