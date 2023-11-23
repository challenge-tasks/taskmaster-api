<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $frontUrl = config('app.front_url');
        $url = $frontUrl . '/recovery?email=' . $notifiable->email . '&token=' . $this->token;

        return (new MailMessage)
            ->subject('Восстановление пароля')
            ->greeting('Здравствуйте, ' . $notifiable->username)
            ->line('Вы получили это письмо, потому что запросили восстановление пароля для вашего аккаунта на нашем сайте.')
            ->line('Для установки нового пароля, пожалуйста, перейдите по следующей ссылке:')
            ->action('Восстановить пароль', $url)
            ->line('Если вы не запрашивали восстановление пароля, пожалуйста, проигнорируйте это письмо.');
    }
}
