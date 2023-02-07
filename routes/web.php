<?php

use Illuminate\Support\Facades\Route;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return view('auth.login');
})->middleware(['auth']);

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/machine_jobs', [App\Http\Controllers\MachineJobController::class, 'index'])->name('machine_jobs');

    Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers');
    Route::get('/carts', [App\Http\Controllers\CartController::class, 'index'])->name('carts');
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products');
    Route::get('/packages', [App\Http\Controllers\PackageController::class, 'index'])->name('packages');
});
