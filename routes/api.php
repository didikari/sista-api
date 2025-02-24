<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\GuidanceController;
use App\Http\Controllers\Api\GuidanceHistoryController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PdfController;
use App\Http\Controllers\Api\PreSeminarController;
use App\Http\Controllers\Api\SeminarController;
use App\Http\Controllers\Api\TitleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(
    function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('refresh', [AuthController::class, 'refresh']);
    }
);

// middleware ke seluruh grup rute
Route::middleware('jwtAuth')->group(function () {

    Route::prefix('pdf')->group(function () {
        Route::get('/generate/{type}/{id}', [PdfController::class, 'generate'])->name('pdf.generate');
        Route::get('/preview/{type}/{id}', [PdfController::class, 'preview'])->name('pdf.preview');
    });

    // Route untuk prefix grades
    Route::prefix('users')->group(function () {
        Route::get('/dosen', [UserController::class, 'index']);
    });
    // Rute untuk prefix auth
    Route::prefix('auth')->group(function () {
        Route::post('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });

    // Rute untuk prefix title
    Route::prefix('titles')->group(function () {

        Route::get('/', [TitleController::class, 'index']);

        // Route::middleware('role:admin')->group(function () {
        //     Route::get('/', [TitleController::class, 'getAll']);
        // });

        Route::middleware('role:mahasiswa')->group(function () {
            Route::get('/student', [TitleController::class, 'getByStudent']);
            Route::get('/student/{id}', [TitleController::class, 'show']);
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
            Route::get('/{id}', [PaymentController::class, 'show']);
        });

        Route::middleware('role:mahasiswa|staff|admin')->group(function () {
            Route::get('/', [PaymentController::class, 'index']);
        });
    });

    // Rute untuk prefix guidances
    Route::prefix('guidances')->group(function () {

        Route::middleware('role:mahasiswa|dosen')->group(function () {
            Route::get('/', [GuidanceController::class, 'index']);
        });



        Route::middleware('role:mahasiswa')->group(function () {
            Route::get('/student/{id}', [GuidanceController::class, 'show']);
            Route::post('/store', [GuidanceController::class, 'store']);
            Route::patch('/update/{id}', [GuidanceController::class, 'update']);
        });

        Route::middleware('role:dosen')->group(function () {
            Route::patch('/supervisor/update/{id}', [GuidanceController::class, 'updateBySupervisor']);
        });

        Route::middleware('role:mahasiswa|dosen|kaprodi')->group(function () {
            Route::get('/histories', [GuidanceHistoryController::class, 'index']);
        });
    });

    // Rute untuk prefix pre-seminars
    Route::prefix('pre-seminars')->group(function () {
        Route::middleware('role:mahasiswa|kaprodi|admin|dosen')->group(function () {
            Route::get('/', [PreSeminarController::class, 'index']);
        });

        Route::middleware('role:mahasiswa')->group(function () {
            Route::post('/store', [PreSeminarController::class, 'store']);
        });

        Route::middleware('role:mahasiswa|kaprodi|admin')->group(function () {
            Route::get('/{id}', [PreSeminarController::class, 'show']);
            Route::patch('/update/{id}', [PreSeminarController::class, 'update']);
        });
    });

    // Rute untuk prefix seminars
    Route::prefix('seminars')->group(function () {

        Route::middleware('role:mahasiswa|dosen|kaprodi|admin')->group(function () {
            Route::get('/', [SeminarController::class, 'index']);
        });

        Route::middleware('role:mahasiswa')->group(function () {
            Route::post('/store', [SeminarController::class, 'store']);
        });

        Route::middleware('role:kaprodi|mahasiswa|admin')->group(function () {
            Route::get('/{id}', [SeminarController::class, 'show']);
            Route::patch('/update/{id}', [SeminarController::class, 'update']);
        });
    });

    // Rute untuk prefix exams
    Route::prefix('exams')->group(function () {

        Route::middleware('role:mahasiswa|dosen|kaprodi|admin')->group(function () {
            Route::get('/', [ExamController::class, 'index']);
        });

        Route::middleware('role:mahasiswa')->group(function () {
            Route::post('/store', [ExamController::class, 'store']);
        });

        Route::middleware('role:kaprodi|mahasiswa|admin')->group(function () {
            Route::get('/{id}', [ExamController::class, 'show']);
            Route::patch('/update/{id}', [ExamController::class, 'update']);
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
