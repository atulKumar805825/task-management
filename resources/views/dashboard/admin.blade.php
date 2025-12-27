@extends('layouts.app')

@section('content')
<div class="container">

{{-- Cards --}}
<div class="row g-3 mb-4">
  @foreach([
    'Total Tasks' => $totalTasks,
    'Pending Tasks' => $pendingTasks,
    'In Progress Tasks' => $inProgressTasks,
    'Completed Tasks' => $completedTasks
  ] as $label => $count)
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h6>{{ $label }}</h6>
          <h3>{{ $count }}</h3>
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- Charts --}}
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">Task Completion Rate</div>
      <div class="card-body">
        <canvas id="taskChart"></canvas>
      </div>
    </div>
  </div>

  
  {{-- Notifications --}}
  <div class="col-md-4">
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

<div class="row">
    <div class="col-md-4">
        {{-- User Performance --}}
        <div class="card mt-4">
        <div class="card-header">User Performance</div>
        <table class="table mb-0">
            <tr>
            <th>User</th>
            <th>Completed Tasks</th>
            </tr>
            @foreach($userPerformance as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->completed_tasks }}</td>
            </tr>
            @endforeach
        </table>
        </div>
    </div>
    <!-- Overdue Tasks Chart -->
    <div class="col-md-8 mt-4">
        <div class="card shadow-sm">
        <div class="card-header">
            Overdue Tasks (Month Wise)
        </div>

        <div class="card-body">
            <canvas id="overdueChart"></canvas>
        </div>
        </div>
    </div>
    <div>
</div>
  </div>
</div>


@endsection
@push('scripts')
<script>
const ctx = document.getElementById('taskChart');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: {!! json_encode($monthlyStats->pluck('month')) !!},
    datasets: [{
      label: 'Tasks',
      data: {!! json_encode($monthlyStats->pluck('total')) !!},
      borderColor: '#0d6efd'
    }]
  }
});
</script>

<script>
const overdueCtx = document.getElementById('overdueChart');

new Chart(overdueCtx, {
  type: 'bar',
  data: {
    labels: {!! json_encode(
      $overdueStats->pluck('month')->map(fn($m) =>
        \Carbon\Carbon::create()->month($m)->format('M')
      )
    ) !!},
    datasets: [{
      label: 'Overdue Tasks',
      data: {!! json_encode($overdueStats->pluck('total')) !!},
      backgroundColor: '#dc3545'
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { precision: 0 }
      }
    }
  }
});
</script>
<script>
  
document.addEventListener('DOMContentLoaded', function () {

    Echo.private('users.{{ auth()->id() }}')
        .notification((notification) => {

           

            const message = notification.message;
            const time = notification.time;
            
            toastr.info(message);
            console.log('Echo notification:', message);
            const list = document.getElementById('notification-list');
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

@endpush
