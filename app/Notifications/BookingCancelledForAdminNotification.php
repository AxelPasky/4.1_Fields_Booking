<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledForAdminNotification extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
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
        $user = $this->booking->user;
        $field = $this->booking->field;
        $bookingDate = $this->booking->start_time->format('d M Y, H:i');

        return (new MailMessage)
                    ->subject('User Booking Cancellation Alert')
                    ->line('A user has cancelled a booking.')
                    ->line('User: ' . $user->name . ' (' . $user->email . ')')
                    ->line('Field: ' . $field->name)
                    ->line('Booking Time: ' . $bookingDate)
                    ->action('View All Bookings', route('bookings.index'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
