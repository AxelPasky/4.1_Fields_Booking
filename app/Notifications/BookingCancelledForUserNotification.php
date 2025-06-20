<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledForUserNotification extends Notification
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
        $field = $this->booking->field;
        $bookingDate = $this->booking->start_time->format('d M Y, H:i');

        return (new MailMessage)
                    ->subject('Your Booking Has Been Cancelled')
                    ->line('An administrator has cancelled one of your bookings.')
                    ->line('Field: ' . $field->name)
                    ->line('Booking Time: ' . $bookingDate)
                    ->line('If you have any questions, please contact support.')
                    ->action('View Your Bookings', route('bookings.index'));
    }
}
