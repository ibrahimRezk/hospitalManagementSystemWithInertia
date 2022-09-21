<?php
use App\Http\Controllers\Admin\AmbulancesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeleteImageController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\DoctorsController;
use App\Http\Controllers\Admin\InsurancesController;
use App\Http\Controllers\Admin\LaboratoristsController;
use App\Http\Controllers\Admin\PatientsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\RadiologistsController;
use App\Http\Controllers\Admin\ReceiptsController;
use App\Http\Controllers\Admin\SectionsController;
use App\Http\Controllers\Admin\ServicesGroupInvoicesController;
use App\Http\Controllers\Admin\ServicesGroupsController;
use App\Http\Controllers\Admin\SingleServiceInvoicesController;
use App\Http\Controllers\Admin\SingleServicesController;
use App\Http\Controllers\Admin\UploadImagesController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;





Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
    
    // model with multiple controllers 
    // To achieve this with a resource you'll need to manually name the resource parameters, which would effectively be something like Route::resource('two', 'TwoController')->parameters(['two' => 'myModel']) where myModel would be your model name.
    




    /// for profile route to work on jetstream
    $authMiddleware = config('jetstream.guard') 
    ? 'auth:'.config('jetstream.guard')
    : 'auth';
    
    $authSessionMiddleware = config('jetstream.auth_session', false)
    ? config('jetstream.auth_session')
    : null;
    
    Route::group(['middleware' => array_values(array_filter([$authMiddleware, $authSessionMiddleware ]))], function () {
    Route::get('/user/profile', [UserProfileController::class, 'show'])
                ->name('profile.show')
                ->middleware(['Lang' ]);
});



////// other routes///////////

Route::middleware(['auth', 'Lang'])->group(function () {
    
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('users', UsersController::class);
    Route::resource('doctors', DoctorsController::class)->parameters(['doctors' => 'user']);  // this to allow rout model binding in different controllers with one model and solve user request validation unique problems 'doctors' for the resource name  and 'user' for the model name
    Route::resource('radiologists', RadiologistsController::class)->parameters(['radiologists' => 'user']);
    Route::resource('laboratorists', LaboratoristsController::class)->parameters(['laboratorists' => 'user']);
    Route::resource('patients', PatientsController::class)->parameters(['patients' => 'user']);
    
    Route::resource('sections', SectionsController::class);

    // important : any extra route must be put before resource route or it will not work
    Route::resource('singleServices', SingleServicesController::class)->parameters(['singleServices' => 'service']);
    Route::resource('servicesGroups', ServicesGroupsController::class)->parameters(['servicesGroups' => 'group']);
    Route::resource('insurances', InsurancesController::class);
    Route::resource('ambulances', AmbulancesController::class);
    Route::resource('singleInvoices', SingleServiceInvoicesController::class)->parameters(['singleInvoices' => 'invoice']);
    Route::resource('servicesGroupInvoices', ServicesGroupInvoicesController::class)->parameters(['servicesGroupInvoices' => 'invoice']);
    Route::resource('receipts', ReceiptsController::class); 
    Route::resource('payments', PaymentsController::class); 


    Route::post('upload-images' , UploadImagesController::class )->name('images.store');
    Route::delete('delete-image/{id}' ,DeleteImageController::class)->name('images.destroy');








//////////// to change lang /////////
    Route::get('/change_lang/{locale}', function ($locale) {
        App::setLocale($locale);
        session()->put('lang', $locale);
        return redirect()->back();
    })->name('lang');
});



// require_once __DIR__ . '/jetstreamInertia.php';
// require_once __DIR__ . '/fortifyAdmin.php';