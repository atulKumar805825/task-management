@extends('layouts.app')

@section('content')
<div class="container mt-4">

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- TASK DETAILS -->
<div class="card mb-3">
  <div class="card-header bg-primary text-white">Task Details</div>
  <div class="card-body">
    <h5>{{ $task->title }}</h5>
    <p>{{ $task->description }}</p>

    <span class="badge bg-{{ task_status_color($task->status) }}">{{ ucfirst(str_replace('_',' ', $task->status)) }}</span>
       
    <span class="badge bg-{{ task_priority_color($task->priority) }}">{{ ucfirst($task->priority) }}</span>
       
    <span class="badge bg-secondary">{{ $task->due_date }}</span>
  </div>
</div>

<!-- UPDATE STATUS (ADMIN + USER) -->
<div class="card mb-3">
  <div class="card-header">Update Status</div>
  <div class="card-body">
    <form method="POST" action="{{ route('tasks.status',$task->id) }}">
      @csrf
      @method('PUT')

      <div class="input-group">
        <select name="status" class="form-select">
          <option value="pending" @selected($task->status=='pending')>Pending</option>
          <option value="in_progress" @selected($task->status=='in_progress')>In Progress</option>
          <option value="completed" @selected($task->status=='completed')>Completed</option>
        </select>
        <button class="btn btn-success">Update</button>
      </div>
    </form>
  </div>
</div>

<!-- ASSIGNED USERS (ADMIN + USER) -->
<div class="card mb-3">
  <div class="card-header bg-dark text-white">Assigned Users</div>

  <ul class="list-group list-group-flush">
    @foreach($assignedUsers as $user)
      <li class="list-group-item d-flex justify-content-between align-items-center">
        {{ $user->name }} ({{ $user->email }})

        @if(auth()->user()->role === 'admin')
        <form method="POST"
              action="{{ route('tasks.assign.remove', [$task->id, $user->id]) }}"
              onsubmit="return confirm('Are you sure you want to remove this user from the task?');">
          @csrf
          @method('DELETE')
          <button class="btn btn-sm btn-danger" type="submit" >
            Remove
          </button>
        </form>
        @endif
      </li>
    @endforeach
  </ul>
</div>

<!-- ASSIGN MORE USERS (ADMIN ONLY) -->
@if(auth()->user()->role === 'admin')
<div class="card">
  <div class="card-header bg-success text-white">
    Assign More Users
  </div>

  <div class="card-body">
    @if($availableUsers->count())
      <form method="POST"
            action="{{ route('tasks.assign.more', $task->id) }}">
        @csrf
        @method('PUT')

        <select name="users[]"
                id="usersSelect"
                class="form-select mb-2 mt-2"
                multiple>
          @foreach($availableUsers as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
          @endforeach
        </select>

        <button class="btn btn-success mt-2 w-100">
          Assign Users
        </button>
      </form>
    @else
      <p class="text-muted">All users are already assigned.</p>
    @endif
  </div>
</div>
@endif

</div>
@endsection
