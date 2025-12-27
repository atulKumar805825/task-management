<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TaskAssignedNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $taskTitle;

    public function __construct($taskTitle)
    {
        $this->taskTitle = $taskTitle;
    }

    // Store in DB
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "New task '{$this->taskTitle}' has been assigned to you.",
        ];
    }

    // Real-time broadcast
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "New task '{$this->taskTitle}' has been assigned to you.",
            'time' => now()->format('h:i A')
        ]);
    }
}
