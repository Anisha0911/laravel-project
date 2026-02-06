<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskNotification extends Notification
{
    public function __construct(
        public $message,
        public $taskId
    ) {}

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'task_id' => $this->taskId,
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Task Update')
            ->line($this->message)
            ->action('View Task', url('/tasks/'.$this->taskId))
            ->line('Thank you!');
    }
}

