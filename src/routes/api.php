<?php

use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\UpdatePasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\MessagesController;
use App\Http\Controllers\Api\PatientsController;
use App\Http\Controllers\Api\TestsController;
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
    Route::post('/register', [RegisterController::class, 'register'])->name('api.register');
    Route::post('/login', [LoginController::class, 'login'])->name('api.login');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('api.reset.password');
    Route::post('/update-password', [UpdatePasswordController::class, 'update'])->name('api.password.update');

    Route::middleware(['bearer'])->group(function () {
        Route::get('/logout', [LogoutController::class, 'logout'])->name('api.logout');
        Route::get('/profile', [ProfileController::class, 'profile'])->name('api.profile');

        Route::prefix('patients')->group(function () {
            Route::get('/', [PatientsController::class, 'list'])->name('api.patients.list');
            Route::put('/', [PatientsController::class, 'update'])->name('api.patients.update');
            Route::get('/{id}', [PatientsController::class, 'info'])->name('api.patients.info')->where('id', '[0-9]+');
            Route::post('/create', [PatientsController::class, 'create'])->name('api.patients.create');
            Route::post('/add-test', [PatientsController::class, 'addTest'])->name('api.patients.add-test');
            Route::get('/{id}/tests', [PatientsController::class, 'listTest'])->name('api.patients.list-tests')->where('id', '[0-9]+');

            Route::prefix('messages')->group(function () {
                Route::post('/{id}/send', [MessagesController::class, 'send'])->name('api.patients.message.send')->where('id', '[0-9]+');
                Route::post('/{id}/list', [MessagesController::class, 'list'])->name('api.patients.message.list')->where('id', '[0-9]+');
            });
        });

        Route::prefix('tests')->group(function () {
            Route::get('/', [TestsController::class, 'list'])->name('api.tests.tests');
            Route::put('/', [TestsController::class, 'update'])->name('api.tests.update');
            Route::get('/all', [TestsController::class, 'all'])->name('api.tests.all');
            Route::get('/{id}', [TestsController::class, 'info'])->name('api.tests.info')->where('id', '[0-9]+');
            Route::post('/create', [TestsController::class, 'create'])->name('api.tests.create');
        });
    });
});
//});
