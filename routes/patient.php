<?php

use App\Http\Controllers\Patient\PatientDashboardController;
use App\Http\Controllers\Patient\PatientsController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;



/// for profile route to work on jetstream
$authMiddleware = config('jetstream.guard')
    ? 'auth:' . config('jetstream.guard')
    : 'auth';

$authSessionMiddleware = config('jetstream.auth_session', false)
    ? config('jetstream.auth_session')
    : null;

Route::group(['middleware' => array_values(array_filter([$authMiddleware, $authSessionMiddleware]))], function () {
    Route::get('/user/profile', [UserProfileController::class, 'show'])
        ->name('profile.show')
        ->middleware(['Lang']);
});



////// other routes///////////

Route::middleware(['auth', 'Lang'])->group(function () {

    Route::get('dashboard', PatientDashboardController::class)->name('dashboard');


    Route::get('invoices' , [PatientsController::class , 'invoices'] )->name('invoices');
    Route::get('report/{id}' , [PatientsController::class , 'report'] )->name('report');
    Route::get('laboratories' , [PatientsController::class , 'laboratories'] )->name('laboratories');
    Route::get('lab_result/{id}' , [PatientsController::class , 'labResult'] )->name('labResult');
    Route::get('radiologies' , [PatientsController::class , 'radiologies'] )->name('radiologies');
    Route::get('radiology_result/{id}' , [PatientsController::class , 'radiologyResult'] )->name('RadiologyResult');




    //////////// to change lang /////////
    Route::get('/change_lang/{locale}', function ($locale) {
        App::setLocale($locale);
        session()->put('lang', $locale);
        return redirect()->back();
    })->name('lang');
});


