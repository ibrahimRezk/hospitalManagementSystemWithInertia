<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\AttachPermissionToRoleController;
use App\Http\Controllers\Admin\DetachPermissionFromRoleController;
use App\Http\Controllers\Admin\UploadImagesController;
use App\Http\Controllers\Admin\DeleteImageController;
use App\Http\Controllers\Admin\DoctorsController;
use App\Http\Controllers\Admin\SectionsController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\ServicesGroupsController;
use App\Http\Controllers\Admin\SingleServicesController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

// model with multiple controllers 
// To achieve this with a resource you'll need to manually name the resource parameters, which would effectively be something like Route::resource('two', 'TwoController')->parameters(['two' => 'myModel']) where myModel would be your model name.

Route::middleware(['auth', 'Lang'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('users', UsersController::class);
    Route::resource('sections', SectionsController::class);
    Route::resource('doctors', DoctorsController::class)->parameters(['doctors' => 'user']);  // this to allow rout model binding in different controllers with one model and solve user request validation unique problems 'doctors' for the resource name  and 'user' for the model name


    // important : any extra route must be put before resource route or it will not work
    Route::resource('singleServices', SingleServicesController::class)->parameters(['singleServices' => 'service']);
    Route::resource('servicesGroups', ServicesGroupsController::class)->parameters(['servicesGroups' => 'group']);







    Route::get('/change_lang/{locale}', function ($locale) {
        App::setLocale($locale);
        session()->put('lang', $locale);
        return redirect()->back();
    })->name('lang');
});


require_once __DIR__ . '/jetstreamInertia.php';
require_once __DIR__ . '/fortifyAdmin.php';
