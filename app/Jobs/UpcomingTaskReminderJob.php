<?php
namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskDueSoonNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpcomingTaskReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $tasks = Task::where('status', '!=', 'completed')
            ->whereBetween('due_date', [
                Carbon::now(),
                Carbon::now()->addHours(24)
            ])
            ->with('assignees')
            ->get();

        foreach ($tasks as $task) {
            foreach ($task->assignees as $user) {
                $user->notify(
                    new TaskDueSoonNotification($task)
                );
            }
        }
    }
}
