<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DoctorDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd($request);
        return Inertia::render('Doctor/Dashboard');
    }
}
