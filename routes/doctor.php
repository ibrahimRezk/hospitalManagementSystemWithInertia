<?php
use App\Http\Controllers\Doctor\DiagnosesController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\InvoicesController;
use App\Http\Controllers\Doctor\LaboratoriesController;
use App\Http\Controllers\Doctor\PatientDetailsController;
use App\Http\Controllers\Doctor\RadiologiesController;
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

    Route::get('dashboard', DoctorDashboardController::class)->name('dashboard');


    Route::get('completed_invoices', [InvoicesController::class,'completedInvoices'])->name('completedInvoices');
    Route::get('review_invoices', [InvoicesController::class,'reviewInvoices'])->name('reviewInvoices');
    Route::get('patient_details/{id}', [PatientDetailsController::class,'index'])->name('patient_details');
    Route::resource('invoices' , InvoicesController::class);
    
    
    Route::resource('diagnoses', DiagnosesController::class);
    Route::resource('radiologies' , RadiologiesController::class);
    Route::resource('laboratories', LaboratoriesController::class);






    
    //////////// to change lang /////////
    Route::get('/change_lang/{locale}', function ($locale) {
        App::setLocale($locale);
        session()->put('lang', $locale);
        return redirect()->back();
    })->name('lang');
});


