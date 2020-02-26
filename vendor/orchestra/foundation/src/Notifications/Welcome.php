<?php

namespace Orchestra\Foundation\Notifications;

use Illuminate\Notifications\Notification;
use Orchestra\Notifications\Messages\MailMessage;

class Welcome extends Notification
{
    /**
     * The password.
     *
     * @var string|null
     */
    public $password;

    /**
     * Create a notification instance.
     *
     * @param  string|null  $password
     */
    public function __construct($password = null)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the notification message for mail.
     *
     * @param  mixed  $notifiable
     *
     * @return \Orchestra\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $password = $this->password;

        $message = new MailMessage();

        $message->viewData = [
            'email' => $email = $notifiable->getRecipientEmail(),
            'fullname' => $notifiable->getRecipientName(),
        ];

        $message->title(\trans('orchestra/foundation::email.register.title'))
                ->line(\trans('orchestra/foundation::email.register.message.intro'))
                ->line(\trans('orchestra/foundation::email.register.message.email', \compact('email')));

        if (! \is_null($this->password)) {
            $message->line(\trans('orchestra/foundation::email.register.message.password', \compact('password')));
        }

        return $message;
    }
}
