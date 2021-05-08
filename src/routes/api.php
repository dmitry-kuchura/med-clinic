<?php

use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\UpdatePasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\DoctorsController;
use App\Http\Controllers\Api\FirebirdController;
use App\Http\Controllers\Api\LogsController;
use App\Http\Controllers\Api\MessagesController;
use App\Http\Controllers\Api\MessagesTemplatesController;
use App\Http\Controllers\Api\PatientsMessagesController;
use App\Http\Controllers\Api\PatientAppointmentsController;
use App\Http\Controllers\Api\PatientsController;
use App\Http\Controllers\Api\AnalysisController;
use App\Http\Controllers\Api\PatientsVisitsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware(['logging'])->group(function () {
Route::prefix('v1')->group(function () {
    Route::get('/firebird', [FirebirdController::class, 'data'])->name('api.firebird.data');

    Route::post('/register', [RegisterController::class, 'register'])->name('api.register');
    Route::post('/login', [LoginController::class, 'login'])->name('api.login');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('api.reset.password');
    Route::post('/update-password', [UpdatePasswordController::class, 'update'])->name('api.password.update');

    Route::middleware(['bearer'])->group(function () {
        Route::get('/logout', [LogoutController::class, 'logout'])->name('api.logout');
        Route::get('/profile', [ProfileController::class, 'profile'])->name('api.profile');

        Route::prefix('doctors')->group(function () {
            Route::get('/', [DoctorsController::class, 'list'])->name('api.doctors.list');
            Route::get('/{id}', [DoctorsController::class, 'info'])->name('api.doctors.info')->where('id', '[0-9]+');
            Route::get('/approved', [DoctorsController::class, 'approved'])->name('api.doctors.approved');
            Route::post('/search', [DoctorsController::class, 'search'])->name('api.doctors.search');

            Route::prefix('approve')->group(function () {
                Route::post('/', [DoctorsController::class, 'approve'])->name('api.doctors.approve');
                Route::post('/delete', [DoctorsController::class, 'approveDelete'])->name('api.doctors.approve.delete');
            });
        });

        Route::prefix('patients')->group(function () {
            Route::get('/{id}', [PatientsController::class, 'info'])->name('api.patients.info')->where('id', '[0-9]+');
            Route::post('/create', [PatientsController::class, 'create'])->name('api.patients.create');
            Route::put('/', [PatientsController::class, 'update'])->name('api.patients.update');
            Route::get('/', [PatientsController::class, 'list'])->name('api.patients.list');
            Route::post('/search', [PatientsController::class, 'search'])->name('api.patients.search');
        });

        Route::prefix('visits')->group(function () {
            Route::get('/{patientId}/list', [PatientsVisitsController::class, 'list'])->name('api.visits.patient.list')->where('patientId', '[0-9]+');
            Route::get('/approved', [PatientsVisitsController::class, 'approvedList'])->name('api.visits.approved.list');
        });

        Route::prefix('appointments')->group(function () {
            Route::get('/{patientId}/list', [PatientAppointmentsController::class, 'list'])->name('api.patients.appointments.list')->where('patientId', '[0-9]+');
            Route::get('/today', [PatientAppointmentsController::class, 'today'])->name('api.patients.appointments.today');
        });

        Route::prefix('analysis')->group(function () {
            Route::get('/{patientId}/list', [AnalysisController::class, 'list'])->name('api.analysis.list')->where('patientId', '[0-9]+');
            Route::get('/all', [AnalysisController::class, 'all'])->name('api.analysis.all');
            Route::get('/{id}', [AnalysisController::class, 'info'])->name('api.analysis.info')->where('id', '[0-9]+');
            Route::post('/create', [AnalysisController::class, 'create'])->name('api.analysis.create');
        });

        Route::prefix('messages')->group(function () {
            Route::get('/balance', [MessagesController::class, 'balance'])->name('api.message.balance');
            Route::post('/send', [PatientsMessagesController::class, 'send'])->name('api.patients.message.send')->where('id', '[0-9]+');
            Route::get('/{patientId}/list', [PatientsMessagesController::class, 'list'])->name('api.patients.message.list')->where('patientId', '[0-9]+');
        });

        Route::prefix('messages-templates')->group(function () {
            Route::get('/', [MessagesTemplatesController::class, 'list'])->name('api.messages-templates.list');
            Route::put('/', [MessagesTemplatesController::class, 'update'])->name('api.messages-templates.update');
            Route::get('/{id}', [MessagesTemplatesController::class, 'info'])->name('api.messages-templates.info')->where('id', '[0-9]+');
        });

        Route::prefix('logs')->group(function () {
            Route::get('/', [LogsController::class, 'list'])->name('api.logs.list');
        });
    });
});
//});
