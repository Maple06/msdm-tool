<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonthlyReportController;

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

Route::get('/', [ClientController::class, 'index'])->name('client.index');
Route::get('/members/{id}', [ClientController::class, 'show'])->name('client.show');
Route::get('/members/{id}/monthly',[ClientController::class, 'monthly'])->name('client.show.monthly');

Route::group(['prefix'=>'auth'],function(){
    Route::get('/login',[AuthController::class,'page'])->name('login.page');
    Route::post('/login',[AuthController::class,'post'])->name('login.post');
});

Route::prefix('manage')
        ->middleware('auth')
        ->group(function(){
            Route::get('/', [DashboardController::class, 'index'])->name('index');
            Route::resource('activity',ActivityController::class);
            Route::resource('attendance',AttendanceController::class);
            Route::post('attendance/import',[AttendanceController::class,'import'])->name('attendance.import');
            Route::resource('departement',DepartementController::class);
            Route::resource('division',DivisionController::class);
            Route::get('/division/{id}/report',[DivisionController::class,'report'])->name('division.report');
            Route::get('/division/{id}/report/generate',[DivisionController::class,'generate'])->name('division.report.print');
            Route::resource('member',MemberController::class);
            Route::post('member/import',[MemberController::class, 'import'])->name('member.import');
            Route::resource('participant', ParticipantController::class);
            Route::post('/activity/{id}/participants/store', [ParticipantController::class, 'store'])->name('activity.participant.store');
            Route::post('participant/import/{id}',[ParticipantController::class, 'import'])->name('participant.import');
            Route::get('/reports/monthly', [MonthlyReportController::class, 'index'])->name('reports.monthly');
            Route::get('/reports/monthly/pdf', [MonthlyReportController::class, 'generatePDF'])->name('reports.monthly.pdf');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
