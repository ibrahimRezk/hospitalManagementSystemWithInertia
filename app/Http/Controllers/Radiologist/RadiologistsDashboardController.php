<?php

namespace App\Http\Controllers\Radiologist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RadiologistsDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd($request);
        return Inertia::render('Radiologist/Dashboard');
    }
}
