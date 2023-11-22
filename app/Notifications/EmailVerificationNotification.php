<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationNotification extends VerifyEmail
{
    use Queueable;

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $frontUrl = config('app.front_url');
        $url = $frontUrl . '/?id=' . $notifiable->getKey() . '&hash=' . sha1($notifiable->getEmailForVerification());

        return (new MailMessage)
            ->subject('Подтверждение электронной почты')
            ->greeting('Здравствуйте, ' . $notifiable->username)
            ->line('Благодарим вас за регистрацию на нашем сайте. Мы рады приветствовать вас в нашем сообществе!')
            ->line('Для завершения процесса регистрации, пожалуйста, подтвердите ваш адрес электронной почты, нажав на кнопку ниже:')
            ->action('Подтвердить электронную почту', $url)
            ->line('Если вы не регистрировались на нашем веб-сайте, проигнорируйте это письмо. Ваш аккаунт будет активирован только после подтверждения.');
    }
}
