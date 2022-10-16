<?php

use App\Http\Controllers\FrontEnd\HomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });

Route::get('/' , [HomeController::class , 'index'])->name('index');
Route::get('about' , [HomeController::class , 'about'])->name('about');
Route::get('services' , [HomeController::class , 'services'])->name('services');
Route::get('departments' , [HomeController::class , 'departments'])->name('departments');
Route::get('department_single' , [HomeController::class , 'department_single'])->name('department_single');
Route::get('doctors' , [HomeController::class , 'doctors'])->name('doctors');
Route::get('doctor_single' , [HomeController::class , 'doctor_single'])->name('doctor_single');
Route::get('appointment' , [HomeController::class , 'appointment'])->name('appointment');
Route::get('blog_with_sidebar' , [HomeController::class , 'blog_with_sidebar'])->name('blog_with_sidebar');
Route::get('single_blog' , [HomeController::class , 'single_blog'])->name('single_blog');
Route::get('contact' , [HomeController::class , 'contact'])->name('contact');

