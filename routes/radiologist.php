<?php

use App\Http\Controllers\Admin\AmbulancesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\AttachPermissionToRoleController;
use App\Http\Controllers\Admin\DetachPermissionFromRoleController;
use App\Http\Controllers\Admin\UploadImagesController;
use App\Http\Controllers\Admin\DeleteImageController;
use App\Http\Controllers\Admin\DoctorsController;
use App\Http\Controllers\Admin\InsurancesController;
use App\Http\Controllers\Admin\LaboratoristsController;
use App\Http\Controllers\Admin\PatientsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\RadiologistsController;
use App\Http\Controllers\Admin\ReceiptsController;
use App\Http\Controllers\Admin\SectionsController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\ServicesGroupInvoicesController;
use App\Http\Controllers\Admin\ServicesGroupsController;
use App\Http\Controllers\Admin\SingleInvoicesController;
use App\Http\Controllers\Admin\SingleServiceInvoicesController;
use App\Http\Controllers\Admin\SingleServicesController;
use App\Http\Controllers\Doctor\DiagnosesController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\InvoicesController;
use App\Http\Controllers\Doctor\LaboratoriesController;
use App\Http\Controllers\Doctor\PatientDetailsController;
use App\Http\Controllers\Radiologist\RadiologiesController;
use App\Http\Controllers\Radiologist\RadiologistsDashboardController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
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

    Route::get('dashboard', RadiologistsDashboardController::class)->name('dashboard');

    Route::get('completed_invoices' , [RadiologiesController::class , 'completedInvoices'])->name('completedInvoices');
    Route::resource('radiologies', RadiologiesController::class);








    //////////// to change lang /////////
    Route::get('/change_lang/{locale}', function ($locale) {
        App::setLocale($locale);
        session()->put('lang', $locale);
        return redirect()->back();
    })->name('lang');
});


