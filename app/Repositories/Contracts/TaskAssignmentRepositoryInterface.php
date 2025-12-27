<?php
namespace App\Repositories\Contracts;

use App\Models\Task;

interface TaskAssignmentRepositoryInterface
{
    public function assign(Task $task, array $users, int $assignedBy);
    public function remove(Task $task, int $userId);
}
