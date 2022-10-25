<?php

use App\Http\Controllers\Admin\AmbulancesController;
use App\Http\Controllers\Admin\AttachPermissionToRoleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeleteImageController;
use App\Http\Controllers\Admin\DetachPermissionFromRoleController;
use App\Http\Controllers\Admin\DoctorsController;
use App\Http\Controllers\Admin\InsurancesController;
use App\Http\Controllers\Admin\LaboratoristsController;
use App\Http\Controllers\Admin\PatientsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\RadiologistsController;
use App\Http\Controllers\Admin\ReceiptsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SectionsController;
use App\Http\Controllers\Admin\ServicesGroupInvoicesController;
use App\Http\Controllers\Admin\ServicesGroupsController;
use App\Http\Controllers\Admin\SingleServiceInvoicesController;
use App\Http\Controllers\Admin\SingleServicesController;
use App\Http\Controllers\Admin\UploadImagesController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;

// Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');

// model with multiple controllers
// To achieve this with a resource you'll need to manually name the resource parameters, which would effectively be something like Route::resource('two', 'TwoController')->parameters(['two' => 'myModel']) where myModel would be your model name.

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
        ->middleware('Lang');
});

////// other routes///////////

Route::middleware(['auth', 'Lang'])->group(function () {
    // important : any extra route must be put before resource route or it will not work

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::put('update_user/{id}', [UsersController::class, 'updateUser'])->name('users.updateUser');
    Route::resource('users', UsersController::class);
    // ->only(['index', 'store', 'update' , 'edit' , 'destroy' , 'show' , 'create']) ///////// we can specify only the methods we want to use

    Route::resource('roles', RolesController::class);
    Route::post('roles/attach-permission', AttachPermissionToRoleController::class)->name('roles.attach-permission');
    Route::post('roles/detach-permission', DetachPermissionFromRoleController::class)->name('roles.detach-permission');

    Route::put('update_doctor/{id}', [DoctorsController::class, 'updateDoctor'])->name('doctors.updateDoctor');
    Route::resource('doctors', DoctorsController::class)->parameters(['doctors' => 'user']); // parameters to allow rout model binding in different controllers with one model and solve user request validation unique problems 'doctors' for the resource name  and 'user' for the model name ....// it't user not users because it's used in edit and update and delete witch use user not users as instance of User model

    Route::put('update_radiologist/{id}', [RadiologistsController::class, 'updateRadiologist'])->name('radiologists.updateRadiologist');
    Route::resource('radiologists', RadiologistsController::class)->parameters(['radiologists' => 'user']);

    Route::put('update_laboratorist/{id}', [LaboratoristsController::class, 'updateLaboratorist'])->name('laboratorists.updateLaboratorist');
    Route::resource('laboratorists', LaboratoristsController::class)->parameters(['laboratorists' => 'user']);

    Route::put('update_patient/{id}', [PatientsController::class, 'updatePatient'])->name('patients.updatePatient');
    Route::resource('patients', PatientsController::class)->parameters(['patients' => 'user']);

    Route::resource('sections', SectionsController::class);
    Route::resource('singleServices', SingleServicesController::class)->parameters(['singleServices' => 'service']);
    Route::resource('servicesGroups', ServicesGroupsController::class)->parameters(['servicesGroups' => 'group']);
    Route::resource('insurances', InsurancesController::class);
    Route::resource('ambulances', AmbulancesController::class);
    Route::resource('singleInvoices', SingleServiceInvoicesController::class)->parameters(['singleInvoices' => 'invoice']);
    Route::resource('servicesGroupInvoices', ServicesGroupInvoicesController::class)->parameters(['servicesGroupInvoices' => 'invoice']);
    Route::resource('receipts', ReceiptsController::class);
    Route::resource('payments', PaymentsController::class);

    Route::post('upload-images', UploadImagesController::class)->name('images.store');
    Route::delete('delete-image/{id}', DeleteImageController::class)->name('images.destroy');

    //////////// to change lang /////////
    Route::get('/change_lang/{locale}', function ($locale) {
        App::setLocale($locale);
        session()->put('lang', $locale);
        return redirect()->back();
    })->name('lang');
});

// require_once __DIR__ . '/jetstreamInertia.php';
// require_once __DIR__ . '/fortifyAdmin.php';
