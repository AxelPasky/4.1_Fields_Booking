<?php

namespace App\Notifications;

use App\Models\Field;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FieldDeletedNotification extends Notification
{
    use Queueable;

    protected $field;

    /**
     * Create a new notification instance.
     */
    public function __construct(Field $field)
    {
        $this->field = $field;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Booking Cancellation Notice')
                    ->line('We are writing to inform you that the field "' . $this->field->name . '" has been removed by an administrator.')
                    ->line('As a result, all your future bookings for this field have been automatically cancelled.')
                    ->line('We apologize for any inconvenience this may cause.')
                    ->action('View Your Bookings', url('/bookings'))
                    ->line('Thank you for using our application!');
    }
}
