<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskAssignmentController extends Controller
{
    protected $service;

    public function __construct(TaskAssignmentService $service)
    {
       
        $this->service = $service;
    }

    public function assignForm($taskId)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $task = Task::findOrFail($taskId);
        $users = User::where('role','user')->get();

        return view('tasks.assign', compact('task','users'));
    }

    public function assign(Request $request, $taskId)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $request->validate([
            'users' => 'required|array'
        ]);

        $task = Task::findOrFail($taskId);

        $this->service->assign(
            $task,
            $request->users,
            auth()->id()
        );

        return redirect()->route('tasks.index')
            ->with('success','Task assigned');
    }

    public function addMoreUsers(Request $request, $taskId)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $task = Task::findOrFail($taskId);

        $this->service->assignUsers(
            $task,
            $request->users,
            auth()->id()
        );

        return back()->with('success', 'Users assigned successfully');
    }

    public function removeUser($taskId, $userId)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $task = Task::findOrFail($taskId);

        $task->assignees()->detach($userId);

        return back()->with('success', 'User removed from task');
    }

    
}
