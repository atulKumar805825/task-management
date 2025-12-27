<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// User
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;

// Task
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\TaskRepository;

// Task Assignment
use App\Repositories\Contracts\TaskAssignmentRepositoryInterface;
use App\Repositories\TaskAssignmentRepository;

use App\Events\TaskStatusUpdated;
use App\Listeners\NotifyAdminOnTaskStatusUpdated;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
         // User Repository Binding
         $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        // Task Repository Binding
        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );

        // Task Assignment Repository Binding
        $this->app->bind(
            TaskAssignmentRepositoryInterface::class,
            TaskAssignmentRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Event::listen(
            TaskAssigned::class,
            SendTaskAssignedNotification::class
        );
    }
}
