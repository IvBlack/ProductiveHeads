<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Invite extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail(User $notifiable)
    {
        return (new MailMessage)
            ->greeting('Добрый день! Приглашаем вас пройти тестирование.')
            ->subject('Тема уведомления')
            ->line('Дорогой, ' . $notifiable->first_name.'. Добро пожаловать на сайт тестирования Productive Heads.')
            ->line('Данные для входа:')
            ->line('Телефон: ' . $notifiable->phone)
            ->line('Код: ' . $notifiable->auth_code)
            ->action('Войти', config('app.url') . sprintf('#/login?phone=%s&code=%s', $notifiable->phone, $notifiable->auth_code))
            ->line('Спасибо, что используете наше приложение');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
