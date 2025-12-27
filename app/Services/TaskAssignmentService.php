<?php
namespace App\Services;

use App\Repositories\TaskAssignmentRepository;
use App\Repositories\Contracts\TaskAssignmentRepositoryInterface;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;

class TaskAssignmentService
{
    protected $repo;

    public function __construct(TaskAssignmentRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
    
    public function assignUsers(Task $task, array $users, $by) {
        $this->repo->assign($task, $users, $by);
       
        // 🔔 Send notification to each assigned user
        foreach ($users as $userId) {
            $user = User::find($userId);

            if ($user) {
                $user->notify(
                    new TaskAssignedNotification($task->title)
                );
            }
        }
    }

    public function removeUser(Task $task, $userId) {
        $this->repo->remove($task, $userId);
    }
}
