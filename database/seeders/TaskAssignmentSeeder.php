<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $users = User::where('role', 'user')->pluck('id');

        Task::all()->each(function ($task) use ($users, $admin) {

            // Assign task to 2–5 random users
            $assignedUsers = $users->random(rand(2, 5));

            foreach ($assignedUsers as $userId) {
                $task->assignees()->attach($userId, [
                    'assigned_by' => $admin->id
                ]);
            }
        });
    }
}
