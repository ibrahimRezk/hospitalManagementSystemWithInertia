<?php

namespace App\Http\Controllers\Laboratorist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LaboratoristDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd($request);
        return Inertia::render('Laboratorist/Dashboard');
    }
}
