<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\AdminTaskAssignmentController;
use App\Http\Controllers\Api\Admin\AdminTaskController;

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);



Route::middleware('auth:sanctum')->group(function () {

//Logout
Route::post('/logout', [UserController::class, 'logout']);

   
   
});

Route::middleware(['auth:sanctum', 'admin'])
    ->prefix('admin')
    ->as('api.admin.')   // 👈 IMPORTANT
    ->group(function () {

        Route::apiResource('users', AdminUserController::class);

        Route::apiResource('tasks', AdminTaskController::class);
       


        Route::post('/tasks/{id}/assign', [AdminTaskAssignmentController::class,'assign'])
            ->name('tasks.assign');

        Route::get('/tasks/{id}/assignees', [AdminTaskAssignmentController::class,'assignees'])
            ->name('tasks.assignees');

        Route::delete('/tasks/{id}/assignees/{user}', [AdminTaskAssignmentController::class,'remove'])
            ->name('tasks.assignees.remove');
});

