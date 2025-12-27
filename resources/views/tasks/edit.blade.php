@extends('layouts.app')

@section('content')
<div class="container col-md-6 mt-4">

  <div class="card shadow-sm">
    <div class="card-header bg-info text-dark">
      Edit Task
    </div>

    <div class="card-body">
      <form method="POST" action="{{ route('tasks.update', $task->id) }}">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text"
                 name="title"
                 class="form-control"
                 value="{{ old('title', $task->title) }}"
                 required>
        </div>

        <!-- Description -->
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description"
                    class="form-control"
                    rows="3">{{ old('description', $task->description) }}</textarea>
        </div>

        <!-- Priority -->
        <div class="mb-3">
          <label class="form-label">Priority</label>
          <select name="priority" class="form-select" required>
            <option value="low" @selected($task->priority=='low')>Low</option>
            <option value="medium" @selected($task->priority=='medium')>Medium</option>
            <option value="high" @selected($task->priority=='high')>High</option>
          </select>
        </div>

        <!-- Due Date -->
        <div class="mb-3">
          <label class="form-label">Due Date</label>
          <input type="date"
                 name="due_date"
                 class="form-control"
                 value="{{ $task->due_date->format('Y-m-d') }}"
                 required>
        </div>

        <!-- Submit -->
        <button class="btn btn-info w-100">
          Update Task
        </button>
      </form>
    </div>
  </div>

</div>
@endsection
