<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordRecoveredNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Пароль успешно восстановлен')
            ->greeting('Здравствуйте, ' . $notifiable->username)
            ->line('Мы рады сообщить вам, что ваш пароль был успешно восстановлен.')
            ->line('Если вы не производили действий по восстановлению пароля, немедленно свяжитесь с нашей службой поддержки по адресу info@taskmaster.uz');
    }
}
