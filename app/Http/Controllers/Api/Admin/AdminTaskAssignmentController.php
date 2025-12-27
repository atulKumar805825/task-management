<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignTaskRequest;
use App\Http\Resources\UserResource;
use App\Models\Task;
use App\Services\TaskAssignmentService;
use Illuminate\Http\Request;

class AdminTaskAssignmentController extends Controller
{
    protected $service;

    public function __construct(TaskAssignmentService $service)
    {
       $this->service = $service;
    }

    // Assign task to multiple users
    public function assign(AssignTaskRequest $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        $this->service->assignUsers(
            $task,
            $request->users,
            auth()->id()
        );
     

        return response()->json([
            'message' => 'Task assigned successfully'
        ]);
    }

    // List assigned users
    public function assignees($taskId)
    {
        $task = Task::with('assignees')->findOrFail($taskId);

        return UserResource::collection($task->assignees);
    }

    // Remove assigned user
    public function remove($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);

        $this->service->removeUser($task, $userId);

        return response()->json([
            'message' => 'User removed from task'
        ]);
    }
}
