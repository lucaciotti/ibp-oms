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
// Route::get('/', function () {
//     return view('auth.login');
// })->middleware(['auth']);

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/planned_tasks/{id?}', [App\Http\Controllers\PlannedTaskController::class, 'index'])->name('planned_tasks');
    Route::get('/exportxls_tasks', [App\Http\Controllers\PlannedTaskController::class, 'exportXls'])->name('exportxls_tasks');
    Route::get('/exportxls_completed_tasks', [App\Http\Controllers\PlannedTaskController::class, 'exportXls_completed'])->name('exportxls_completed_tasks');
    Route::get('/plan_xls', [App\Http\Controllers\PlanImportFileController::class, 'index'])->name('plan_xls');
    Route::get('/plan_xls/{id}/', [App\Http\Controllers\PlanImportFileController::class, 'rows'])->name('plan_xls_rows');
    Route::get('/exportxls_temptasks', [App\Http\Controllers\PlanImportFileController::class, 'exportXls'])->name('exportxls_temptasks');

    Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers');
    Route::get('/carts', [App\Http\Controllers\CartController::class, 'index'])->name('carts');
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products');
    Route::get('/packages', [App\Http\Controllers\PackageController::class, 'index'])->name('packages');

    
    Route::get('/config/plantypes', [App\Http\Controllers\PlanTypesController::class, 'index'])->name('planTypes');
    Route::get('/config/attributes', [App\Http\Controllers\AttributeController::class, 'index'])->name('attributes');
    Route::get('/config/plantypes/{id}/attributes', [App\Http\Controllers\PlanTypeAttributesController::class, 'index'])->name('planTypesAttribute');
    Route::get('/config/plantypes/{id}/planimporttypes', [App\Http\Controllers\PlanImportTypesController::class, 'index'])->name('planImportTypes');
});

Route::name('user::')->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class)->middleware(['auth']);
    Route::get('/actLike/{id}', [App\Http\Controllers\UserController::class, 'actLike'])->name('actLike')->middleware(['auth']);
    Route::get('/resetPassword/{id}', [App\Http\Controllers\UserController::class, 'sendResetPassword'])->name('resetPassword');
});