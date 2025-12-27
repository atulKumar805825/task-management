<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\DashboardController;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/tasks', [TaskController::class,'index'])->name('tasks.index');

    Route::get('/tasks/{id}', [TaskController::class, 'show'])
        ->name('tasks.show');

    Route::put('/tasks/{id}/status', [TaskController::class, 'updateStatus'])
        ->name('tasks.status');
    

   
    //dashboard  for user and admin
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

    Route::middleware('admin')->group(function () {

        Route::post('/tasks', [TaskController::class,'store'])->name('tasks.store');

        Route::get('/tasks/create', [TaskController::class,'create'])->name('tasks.create');

        Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])
        ->name('tasks.edit');

        Route::put('/tasks/{id}/assign-more',[TaskAssignmentController::class, 'addMoreUsers']
        )->name('tasks.assign.more');

        Route::delete('/tasks/{task}/remove-user/{user}',
        [TaskAssignmentController::class,'removeUser'])
        ->name('tasks.assign.remove');

        Route::put('/tasks/{task}', [TaskController::class, 'update'])
        ->name('tasks.update');


    });
});



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
