<?php
namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(array $filters)
    {
        return Task::with('assignees')
            ->when($filters['status'] ?? null, fn($q,$v)=>$q->where('status',$v))
            ->when($filters['priority'] ?? null, fn($q,$v)=>$q->where('priority',$v))
            ->when($filters['due_date'] ?? null, fn($q,$v)=>$q->whereDate('due_date',$v))
            ->when($filters['assigned_user_id'] ?? null, function ($q, $userId) {
                $q->whereHas('assignees', function ($sub) use ($userId) {
                    $sub->where('users.id', $userId);
                });
            })->orderByDesc('created_at')
            ->paginate(10);
    }

    public function find(int $id): Task
    {
        return Task::with('assignees')->findOrFail($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}

