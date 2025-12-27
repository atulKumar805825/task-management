<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusUpdated
{
    use Dispatchable, SerializesModels;

    public Task $task;
    public int $updatedBy;

    public function __construct(Task $task, int $updatedBy)
    {
        $this->task = $task;
        $this->updatedBy = $updatedBy;
    }
}
