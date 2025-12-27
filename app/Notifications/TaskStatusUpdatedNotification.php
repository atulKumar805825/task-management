<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TaskStatusUpdatedNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $taskTitle;
    protected $status;
    protected $userName;

    public function __construct($taskTitle, $status, $userName)
    {
        $this->taskTitle = $taskTitle;
        $this->status = $status;
        $this->userName = $userName;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Task '{$this->taskTitle}' marked as '{$this->status}' by {$this->userName}.",
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "Task '{$this->taskTitle}' marked as '{$this->status}' by {$this->userName}.",
            'time' => now()->format('h:i A'),
        ]);
    }

            /**
         * Get the type of the notification being broadcast.
         */
        public function broadcastType(): string
        {
            return 'broadcast.message';
        }

   

}
