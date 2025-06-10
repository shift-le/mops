<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class CustomResetPassword extends Notification
{
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $this->email,
        ], false));

        Log::info("🔗 パスワードリセットURL: {$url}");

        return (new MailMessage)
            ->subject('パスワード再設定')
            ->line('以下のリンクからパスワードを再設定してください。')
            ->action('パスワードを再設定', $url)
            ->line('このメールに心当たりがない場合は無視してください。');
    }
}
