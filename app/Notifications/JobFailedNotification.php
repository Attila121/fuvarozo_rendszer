<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Job;

class JobFailedNotification extends Notification
{
    use Queueable;

    protected  $job;

    /**
     * Create a new notification instance.
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Job Failed Notification')
                    ->line('The job with ID ' . $this->job->getJobId() . ' has failed.')
                    ->line('Delivery Address: ' . $this->job->getDeliveryAddress())
                    ->line('Driver: ' . $this->job->getDriverName())
                    ->action('View Job Details', url('/admin/jobs/' . $this->job->getJobId()));

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'job_id' => $this->job->getJobId(),
            'message' => 'Job #' . $this->job->getJobId() . ' has failed',
            'driver_name' => $this->job->getDriverName(),
            'delivery_address' => $this->job->getDeliveryAddress()
        ];

    }

    public function getJob(): Job
    {
        return $this->job;
    }
}
