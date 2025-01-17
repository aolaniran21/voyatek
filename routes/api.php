<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('projects', ProjectController::class);
    Route::resource('projects.employees', EmployeeController::class)->shallow();

    Route::get('projects/dashboard', [ProjectController::class, 'dashboard']);
    Route::get('projects/search', [ProjectController::class, 'search']);

    Route::post('employees/{id}/restore', [EmployeeController::class, 'restore']);

    Route::middleware('role:Admin')->group(function () {
        Route::post('users/{user}/assign-role', [RoleController::class, 'assignRole']);
        Route::post('users/{user}/remove-role', [RoleController::class, 'removeRole']);
    });

    Route::middleware('role:Manager')->group(function () {
        Route::get('/projects/{project}', [ProjectController::class, 'show']);
    });

    Route::middleware('role:Employee')->group(function () {
        Route::get('/employees/{employee}', [EmployeeController::class, 'show']);
    });
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
