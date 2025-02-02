<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\GuidanceController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PreSeminarController;
use App\Http\Controllers\Api\SeminarController;
use App\Http\Controllers\Api\TitleController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(
    function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
    }
);

// middleware ke seluruh grup rute
Route::middleware('jwtAuth')->group(function () {

    // Rute untuk prefix auth
    Route::prefix('auth')->group(function () {
        Route::post('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    // Rute untuk prefix title
    Route::prefix('titles')->group(function () {

        Route::middleware('role:admin')->group(function () {
            Route::get('/', [TitleController::class, 'getAll']);
        });

        Route::middleware('role:mahasiswa')->group(function () {
            Route::get('/student', [TitleController::class, 'getByStudent']);
            Route::post('/store', [TitleController::class, 'store']);
            Route::delete('/destroy/{id}', [TitleController::class, 'destroy']);
            Route::patch('/update/{id}', [TitleController::class, 'update']);
        });

        Route::middleware('role:dosen')->group(function () {
            Route::get('/supervisor', [TitleController::class, 'getBySupervisor']);
            Route::patch('/supervisor/update/{id}', [TitleController::class, 'updateBySupervisor']);
        });
    });

    // Route untuk prefix payment
    Route::prefix('payments')->group(function () {
        Route::middleware('role:mahasiswa')->group(function () {
            Route::post('/store', [PaymentController::class, 'store']);
            Route::patch('/update/{id}', [PaymentController::class, 'update']);
        });
    });

    // Rute untuk prefix guidances
    Route::prefix('guidances')->group(function () {

        Route::middleware('role:admin')->group(function () {
            // Route::get('/', [TitleController::class, 'getAll']);
        });

        Route::middleware('role:mahasiswa')->group(function () {
            Route::post('/store', [GuidanceController::class, 'store']);
            Route::patch('/update/{id}', [GuidanceController::class, 'update']);
        });

        Route::middleware('role:dosen')->group(function () {
            Route::patch('/supervisor/update/{id}', [GuidanceController::class, 'updateBySupervisor']);
        });
    });

    // Rute untuk prefix pre-seminars
    Route::prefix('pre-seminars')->group(function () {

        Route::middleware('role:admin')->group(function () {
            // Route::get('/', [TitleController::class, 'getAll']);
        });

        Route::middleware('role:mahasiswa')->group(function () {
            Route::post('/store', [PreSeminarController::class, 'store']);
            Route::patch('/update/{id}', [PreSeminarController::class, 'update']);
        });

        Route::middleware('role:kaprodi')->group(function () {
            Route::patch('/kaprodi/update/{id}', [PreSeminarController::class, 'updateByKaprodi']);
        });
    });

    // Rute untuk prefix seminars
    Route::prefix('seminars')->group(function () {

        Route::middleware('role:admin')->group(function () {
            // Route::get('/', [TitleController::class, 'getAll']);
        });

        Route::middleware('role:mahasiswa')->group(function () {
            Route::post('/store', [SeminarController::class, 'store']);
            Route::patch('/update/{id}', [SeminarController::class, 'update']);
        });

        Route::middleware('role:kaprodi')->group(function () {
            Route::patch('/kaprodi/update/{id}', [SeminarController::class, 'updateByKaprodi']);
        });
    });

    // Rute untuk prefix exams
    Route::prefix('exams')->group(function () {
        Route::get('/', [ExamController::class, 'index']);
        Route::middleware('role:admin')->group(function () {
            // Route::get('/', [TitleController::class, 'getAll']);
        });

        Route::middleware('role:mahasiswa')->group(function () {
            Route::post('/store', [ExamController::class, 'store']);
            Route::patch('/update/{id}', [ExamController::class, 'update']);
        });

        Route::middleware('role:kaprodi')->group(function () {
            Route::patch('/kaprodi/update/{id}', [ExamController::class, 'updateByKaprodi']);
        });
    });

    // Route untuk prefix grades
    Route::prefix('grades')->group(function () {
        Route::middleware('role:dosen')->group(function () {
            Route::post('/{gradable}/{id}/{role}', [GradeController::class, 'storeGrade']);
            Route::patch('/{gradable}/{id}/{role}', [GradeController::class, 'updateForGrade']);
        });
    });
});
