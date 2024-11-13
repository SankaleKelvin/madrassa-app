<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MadrassaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Public Routes
Route::post('/login', [AuthController::class, 'login']);

Route::get('course', [CourseController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
    //Auth Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    //User Management Routes
    Route::apiResource('users', UserController::class);

    Route::post('location', [LocationController::class, 'createLocation']);
    Route::get('location', [LocationController::class, 'getLocations']);
    Route::get('location/{id}', [LocationController::class, 'getLocation']);
    Route::put('location/{id}', [LocationController::class, 'updateLocation']);
    Route::delete('location/{id}', [LocationController::class, 'deleteLocation']);


    Route::post('madrassa', [MadrassaController::class, 'createMadrassa']);
    Route::get('madrassa', [MadrassaController::class, 'index']);
    Route::get('madrassa/{id}', [MadrassaController::class, 'getMadrassa']);
    Route::put('madrassa/{id}', [MadrassaController::class, 'updateMadrassa']);
    Route::delete('madrassa/{id}', [MadrassaController::class, 'deleteMadrassa']);

    Route::post('student', [StudentController::class, 'createStudent']);
    Route::get('student', [StudentController::class, 'index']);
    Route::get('student/{id}', [StudentController::class, 'getStudent']);
    Route::put('student/{id}', [StudentController::class, 'updateStudent']);
    Route::delete('student/{id}', [StudentController::class, 'deleteStudent']);


    Route::post('course', [CourseController::class, 'createCourse']);
    // Route::get('course', [CourseController::class, 'index']);
    Route::get('course/{id}', [CourseController::class, 'getCourse']);
    Route::put('course/{id}', [CourseController::class, 'updateCourse']);
    Route::delete('course/{id}', [CourseController::class, 'deleteCourse']);

    Route::post('student_course', [StudentCourseController::class, 'createStudentCourse']);
    Route::get('student_course', [StudentCourseController::class, 'index']);
    Route::get('student_course/{id}', [StudentCourseController::class, 'getStudentCourse']);
    Route::put('student_course/{id}', [StudentCourseController::class, 'updateStudentCourse']);
    Route::delete('student_course/{id}', [StudentCourseController::class, 'deleteStudentCourse']);

    Route::post('billing', [BillingController::class, 'createBilling']);
    Route::get('billing', [BillingController::class, 'index']);
    Route::get('billing/{id}', [BillingController::class, 'getBilling']);
    Route::put('billing/{id}', [BillingController::class, 'updateBilling']);
    Route::delete('billing/{id}', [BillingController::class, 'deleteBilling']);

    Route::post('payment', [PaymentController::class, 'createPayment']);
    Route::get('payment', [PaymentController::class, 'index']);
    Route::get('payment/{id}', [PaymentController::class, 'getPayment']);
    Route::put('payment/{id}', [PaymentController::class, 'updatePayment']);
    Route::delete('payment/{id}', [PaymentController::class, 'deletePayment']);
});
