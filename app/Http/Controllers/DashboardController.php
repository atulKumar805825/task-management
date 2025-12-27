<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;



class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // ADMIN Or User dashboard
        $notifications = $user->notifications()
        ->latest()
        ->take(5)
        ->get();

        // USER DASHBOARD
        if ($user->role === 'user') {
            $assignedTasks = $user->assignedTasks();

            
            $data = [
                'totalTasks' => $assignedTasks->count(),
                'pendingTasks' => $assignedTasks->where('status','pending')->count(),
                'inProgressTasks' => $assignedTasks->where('status','in_progress')->count(),
                'completedTasks' => $assignedTasks->where('status','completed')->count(),
                'notifications' => $notifications,
            ];

            return view('dashboard.user', $data);
        }

        $overdueStats = DB::table('tasks')
        ->select(
            DB::raw('MONTH(due_date) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->where('due_date', '<', Carbon::today())
        ->where('status', '!=', 'completed')
        ->whereNull('deleted_at')
        ->groupBy(DB::raw('MONTH(due_date)'))
        ->orderBy('month')
        ->get();
        
        
    //Cache::forget('admin_dashboard_stats');

    $dashboardData = Cache::remember(
        'admin_dashboard_stats',
        now()->addMinutes(5),
        function () {

            $taskStats = Task::selectRaw("
                COUNT(*) as total,
                SUM(status = 'pending') as pending,
                SUM(status = 'in_progress') as in_progress,
                SUM(status = 'completed') as completed
            ")->first();

            return [
                'taskStats' => $taskStats,

                'userPerformance' => User::where('role','user')
                    ->select('id','name')
                    ->withCount(['assignedTasks as completed_tasks' => function ($q) {
                        $q->where('status','completed');
                    }])
                    ->orderByDesc('completed_tasks')
                    ->limit(10)
                    ->get(),

                'monthlyStats' => Task::selectRaw("
                    MONTH(created_at) as month,
                    COUNT(*) as total
                ")->groupBy('month')->get(),

                'overdueStats' => Task::selectRaw("
                    MONTH(due_date) as month,
                    COUNT(*) as total
                ")
                ->where('due_date','<', now())
                ->where('status','!=','completed')
                ->groupBy('month')
                ->get(),
            ];
        }
    );

    return view('dashboard.admin', [
        'totalTasks'      => $dashboardData['taskStats']->total,
        'pendingTasks'    => $dashboardData['taskStats']->pending,
        'inProgressTasks' => $dashboardData['taskStats']->in_progress,
        'completedTasks'  => $dashboardData['taskStats']->completed,
        'userPerformance' => $dashboardData['userPerformance'],
        'monthlyStats'    => $dashboardData['monthlyStats'],
        'overdueStats'    => $dashboardData['overdueStats'],
        'notifications'   => $notifications,
    ]);

    }
}
