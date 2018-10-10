<?php

namespace App\Notifications\Advert;

use App\Entity\Advert\Advert;
use App\Notifications\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class ModerationPassedNotification extends Notification
{
    use Queueable, SerializesModels;

    private $advert;

    /**
     * Create a new notification instance.
     *
     * @param Advert $advert
     */
    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }

    // DI accessors array
    public function via($notifiable)
    {
        return ['mail', SmsChannel::class];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->subject('Moderation is passed')
                ->greeting('Hello!')
                ->line('Your advert successfully passed a moderation.')
                ->action('View Advert', route('adverts.show', $this->advert))
                ->line('Thank you for using our application!');
    }

    public function toSms(): string
    {
        return 'Your advert successfully passed a moderation.';
    }
}
