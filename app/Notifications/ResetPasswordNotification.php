<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseNotification
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->subject('درخواست بازنشانی رمز عبور')
            ->line('شما این ایمیل را دریافت کرده‌اید زیرا ما درخواستی برای بازنشانی رمز عبور برای حساب شما دریافت کردیم.')
            ->action('بازنشانی رمز عبور', url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
            ->line('این لینک بازنشانی رمز عبور تا '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' دقیقه دیگر منقضی می‌شود.')
            ->line('اگر شما درخواست بازنشانی رمز عبور را نداده‌اید، هیچ اقدام دیگری لازم نیست.');
    }
}
