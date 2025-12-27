@extends('layouts.app')

@section('content')
<div class="container">

<div class="row g-3">
  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h6>Total Tasks</h6>
        <h3>{{ $totalTasks }}</h3>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h6>Pending</h6>
        <h3>{{ $pendingTasks }}</h3>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h6>In Progress</h6>
        <h3>{{ $inProgressTasks }}</h3>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <h6>Completed</h6>
        <h3>{{ $completedTasks }}</h3>
      </div>
    </div>
  </div>
</div>
<div class="row g-3 mt-3">
{{-- Notifications --}}
<div class="col-md-6">
  <div class="card shadow-sm">
    <div class="card-header fw-bold">
      Notifications
    </div>

    <ul class="list-group list-group-flush" id="notification-list">

      @forelse($notifications as $notification)
        <li class="list-group-item d-flex gap-2">
          <div>
            <span class="badge bg-light text-secondary rounded-circle p-2">
              <i class="bi bi-bell"></i>
            </span>
          </div>

          <div>
            <div class="small">
              {{ $notification->data['message'] }}
            </div>
            <div class="text-muted small">
              {{ $notification->created_at->format('h:i A') }}
            </div>
          </div>
        </li>
      @empty
        <li class="list-group-item text-muted text-center">
          No notifications
        </li>
      @endforelse

    </ul>
  </div>
</div>
</div>
</div>
@endsection
<script>
  
document.addEventListener('DOMContentLoaded', function () {

    Echo.private('users.{{ auth()->id() }}')
        .notification((notification) => {

           const message = notification.message;
            const time = notification.time;
            
            console.log('Echo notification:', message);
            const list = document.getElementById('notification-list');
            
            toastr.info(message);
            
            if (!list) return;


            list.insertAdjacentHTML('afterbegin', `
                <li class="list-group-item d-flex gap-2">
                  <div>
                    <span class="badge bg-light text-secondary rounded-circle p-2">
                      <i class="bi bi-bell"></i>
                    </span>
                  </div>
                  <div>
                    <div class="small">${message}</div>
                    <div class="text-muted small">${time}</div>
                  </div>
                </li>
            `);
        });

});


</script>