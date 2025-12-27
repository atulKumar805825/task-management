<?php
namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDueSoonNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Task Due Soon Reminder')
            ->line("Your task '{$this->task->title}' is due within 24 hours.")
            ->line('Due Date: ' . $this->task->due_date->format('d M Y'))
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('Please complete it on time.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Reminder: Task '{$this->task->title}' is due within 24 hours.",
            'task_id' => $this->task->id,
            'due_date' => $this->task->due_date
        ];
    }
}
