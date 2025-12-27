<?php
namespace App\Http\Controllers;

use App\Services\TaskService;
use App\Services\TaskAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Events\TaskStatusUpdated;


class TaskController extends Controller
{
    protected $taskService;
    protected $taskAssignmentService;


    public function __construct(TaskService $taskService,TaskAssignmentService $taskAssignmentService)
    {
        //$this->middleware('auth');
        $this->taskService = $taskService;
        $this->taskAssignmentService = $taskAssignmentService;
    }

    // Admin & User view (WITH FILTERS)
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'priority', 'due_date']);

        // 👑 Admin: allow user filter
        if (auth()->user()->role === 'admin') {
            $filters['assigned_user_id'] = $request->assigned_user_id ?? null;
            $users = \App\Models\User::where('role', 'user')->get();
        } 
        // 👤 User: restrict to own tasks
        else {
            $filters['assigned_user_id'] = auth()->id();
            $users = collect(); // empty
        }

        $tasks = $this->taskService->list($filters);

        return view('tasks.index', compact('tasks','users'));
    }

    // Admin only
    public function create()
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        $users = User::where('role', 'user')->get();
        return view('tasks.create',compact('users'));
    }

    // Admin only
    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $request->validate([
            'title' => 'required',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
            'users' => 'required|array'
        ]);

        //print_r($request->all());die;
        // DB::beginTransaction();

        // try 
        // {
            // save task
            $task = $this->taskService->create([
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'due_date' => $request->due_date,
                'status' => 'pending',
                'user_id' => auth()->id(), // admin creator
            ]);

          
             
            // Assign users
            $this->taskAssignmentService->assignUsers(
                $task,
                $request->users,
                auth()->id()
            );

        //     DB::commit(); // ✅ success
        // } catch (\Throwable $e) {
        
        //     DB::rollBack(); // 🔄 rollback
        
        //     return back()->with('error', 'Task creation failed');
        // }

        return redirect()->route('tasks.index')
            ->with('success', 'Task created');
    }

     // View single task + assigned users
     public function show($id)
     {
         $task = $this->taskService->get($id);
 
         // User security: must be assigned
         if (
             auth()->user()->role === 'user' &&
             !$task->assignees->contains(auth()->id())
         ) {
             abort(403);
         }

      // Assigned users visible to BOTH admin & user
      $assignedUsers = $task->assignees;

      // Available users ONLY for admin (exclude already assigned)
      $availableUsers = collect();
  
      if (auth()->user()->role === 'admin') {
          $availableUsers = \App\Models\User::where('role', 'user')
              ->whereNotIn('id', $assignedUsers->pluck('id'))
              ->get();
      }
  
      return view('tasks.show', compact(
          'task',
          'assignedUsers',
          'availableUsers'
      ));
     }

    // Update task status (Admin & User)
    public function updateStatus(Request $request, $id)
    {
         $request->validate([
             'status' => 'required|in:pending,in_progress,completed'
         ]);
 
         $task = $this->taskService->get($id);
 
         if (
             auth()->user()->role === 'user' &&
             !$task->assignees->contains(auth()->id())
         ) {
             abort(403);
         }
 
         $task = $this->taskService->update($id, [
             'status' => $request->status
         ]);

        // 🔥 Fire event (used by both API & Web)
        event(new TaskStatusUpdated($task, auth()->user()->id));
 
         return back()->with('success', 'Task status updated');
    }

     // Admin edit page
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    // Admin update task
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
        ]);

        $this->taskService->update($task->id, $request->only([
            'title',
            'description',
            'priority',
            'due_date',
        ]));

        return redirect()
            ->route('tasks.show', $task->id)
            ->with('success', 'Task updated successfully');
    }
}
