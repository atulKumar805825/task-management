@extends('layouts.app')

@section('content')
<div class="container">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Tasks</h3>

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            Create Task
        </a>
    @endif
</div>

<div>
  <form method="GET" class="row g-2 mb-3">

  <!-- Status -->
  <div class="col-md-3">
    <label class="mb-1">All Status</label>
    <select name="status" class="form-select">
      <option value="">All</option>
      <option value="pending" @selected(request('status')=='pending')>Pending</option>
      <option value="in_progress" @selected(request('status')=='in_progress')>In Progress</option>
      <option value="completed" @selected(request('status')=='completed')>Completed</option>
    </select>
  </div>

  <!-- Priority -->
  <div class="col-md-3">
  <label class="mb-1">All Priority</label>
    <select name="priority" class="form-select">
      <option value="">All</option>
      <option value="low" @selected(request('priority')=='low')>Low</option>
      <option value="medium" @selected(request('priority')=='medium')>Medium</option>
      <option value="high" @selected(request('priority')=='high')>High</option>
    </select>
  </div>

  <!-- Due Date -->
  <div class="col-md-3">
  <label class="mb-1">Due Date</label>
    <input type="date"
           name="due_date"
           value="{{ request('due_date') }}"
           class="form-control">
  </div>

  <!-- 👑 Admin Only: User Filter -->
  @if(auth()->user()->role === 'admin')
  <div class="col-md-3">
  <label class="mb-1">All Users</label>
    <select name="assigned_user_id" class="form-select">
      <option value="">All</option>
      @foreach($users as $user)
        <option value="{{ $user->id }}"
          @selected(request('assigned_user_id')==$user->id)>
          {{ $user->name }}
        </option>
      @endforeach
    </select>
  </div>
  @endif

  <!-- Submit -->
  <div class="col-md-12">
    <button class="btn btn-primary">Filter</button>
    <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Reset</a>
  </div>
</form>

</div>
  <table class="table table-bordered table-responsive">
    <thead>
      <tr>
        <th>Title</th>
        <th>Status</th>
        <th>Priority</th>
        <th>Due</th>
        
          <th>Action</th>
       
      </tr>
    </thead>
    <tbody>
      @foreach($tasks as $task)
      <tr>
        <td>{{ $task->title }}</td>
        <td>
          <span class="badge bg-{{ task_status_color($task->status) }}">{{ ucfirst(str_replace('_',' ', $task->status)) }}</span>
        </td>
        <td>
          <span class="badge bg-{{ task_priority_color($task->priority) }}">{{ ucfirst($task->priority) }}</span>
        </td>
        <td>{{ $task->due_date }}</td>

       
        <td>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('tasks.edit', $task->id) }}"
          class="btn btn-sm btn-warning">
          <i class="bi bi-pencil"></i>
        </a>
          @endif
          <a href="{{ route('tasks.show',$task->id) }}"
             class="btn btn-sm btn-primary">
             <i class="bi bi-eye"></i>
          </a>
        </td>
       
      </tr>
      @endforeach
    </tbody>
  </table>
  <!-- ✅ Bootstrap 5 Pagination -->
  <div class="d-flex justify-content-center">
    {{ $tasks->withQueryString()->links() }}
  </div>
</div>
@endsection
