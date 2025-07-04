<?php
use App\Http\Controllers\Radiologist\RadiologiesController;
use App\Http\Controllers\Radiologist\RadiologistDashboardController;
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

    Route::get('dashboard', RadiologistDashboardController::class)->name('dashboard');

    Route::get('completed_invoices' , [RadiologiesController::class , 'completedInvoices'])->name('completedInvoices');
    Route::resource('radiologies', RadiologiesController::class);








    //////////// to change lang /////////
    Route::get('/change_lang/{locale}', function ($locale) {
        App::setLocale($locale);
        session()->put('lang', $locale);
        return redirect()->back();
    })->name('lang');
});


