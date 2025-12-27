<?php
namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskAssignmentRepositoryInterface;

class TaskAssignmentRepository implements TaskAssignmentRepositoryInterface
{
    public function assign(Task $task, array $users, int $assignedBy)
    {
        foreach ($users as $userId) {
            $task->assignees()->syncWithoutDetaching([
                $userId => ['assigned_by' => $assignedBy]
            ]);
        }
    }

    public function remove(Task $task, int $userId)
    {
        $task->assignees()->detach($userId);
    }
}

