<?php

namespace App\Listeners;

use App\Events\TaskStatusUpdated;
use App\Models\User;
use App\Notifications\TaskStatusUpdatedNotification;

class NotifyAdminOnTaskStatusUpdated
{
    public function handle(TaskStatusUpdated $event): void
    {
        $task = $event->task;
       
        $user = User::find($event->updatedBy);
       

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(
                new TaskStatusUpdatedNotification(
                    $task->title,
                    $task->status,
                    $user->name
                )
            );
        }
    }
}
