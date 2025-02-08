<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'auth'],function(){
    Route::get('/login',[AuthController::class,'page'])->name('login.page');
    Route::post('/login',[AuthController::class,'post'])->name('login.post');
});

Route::prefix('manage')
        // ->middleware('auth')
        ->group(function(){
            Route::resource('activity',ActivityController::class);
            Route::resource('attendance',AttendanceController::class);
            Route::resource('departement',DepartementController::class);
            Route::resource('division',DivisionController::class);
            Route::resource('member',MemberController::class);

});
