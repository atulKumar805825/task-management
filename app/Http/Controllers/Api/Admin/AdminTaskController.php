<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
       
        $tasks = $this->taskService->list($request->all()??[]);
        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->create($request->validated());

        return new TaskResource($task);
    }

    public function show($id)
    {
        $task = $this->taskService->get($id);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, $id) 
    {
        
        $task = $this->taskService->update($id, $request->validated());

        return new TaskResource($task);
    }

    public function destroy($id)
    {
        $this->taskService->delete($id);

        return response()->json(['message' => 'Task deleted']);
    }
}
