@extends('layouts.app')

@section('content')
<div class="container col-md-6 mt-4">

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      Create Task
    </div>

    <div class="card-body">

      <form method="POST" action="{{ route('tasks.store') }}">
        @csrf

        <!-- Title -->
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input class="form-control"
                 name="title"
                 placeholder="Task title"
                 required>
        </div>

        <!-- Description -->
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control"
                    name="description"
                    rows="3"
                    placeholder="Task description"></textarea>
        </div>

        <!-- Priority -->
        <div class="mb-3">
          <label class="form-label">Priority</label>
          <select class="form-select" name="priority" required>
            <option value="">Select priority</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>
        </div>

        <!-- Due Date -->
        <div class="mb-3">
          <label class="form-label">Due Date</label>
          <input type="date"
                 class="form-control"
                 name="due_date"
                 required>
        </div>

        <!-- Assign Users (Select2) -->
        <div class="mb-3">
          <label class="form-label">Assign Users</label>
          <select name="users[]"
                  id="usersSelect"
                  class="form-select"
                  multiple
                  required>
            @foreach($users as $user)
              <option value="{{ $user->id }}">
                {{ $user->name }} ({{ $user->email }})
              </option>
            @endforeach
          </select>
        </div>

        <!-- Submit -->
        <button class="btn btn-success w-100">
          Create & Assign Task
        </button>

      </form>
    </div>
  </div>
</div>
@endsection
