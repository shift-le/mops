<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class CustomResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // メール本文（テキスト）を配列で構築
        $lines = [
            'Mopsオンデマンド印刷発注システムのパスワードリセットの申請を受け付けました。',
            'パスワードの再設定をご希望の場合は、以下のURLをクリックして、新しいパスワードをご登録ください。',
            '▼パスワードの再設定URL',
            $resetUrl,
            '※このメールは自動送信されています。内容にお心当たりがない場合は、破棄をお願いいたします。',
            '------------------------------',
            '【問い合わせ窓口】',
            'マルホ株式会社：eigyoukanri_mops@mii.maruho.co.jp',
            '------------------------------',
        ];

        // ログ出力（改行で結合）
        // Log::info('[パスワードリセットメール（テキスト）]', [
        //     'to' => $notifiable->getEmailForPasswordReset(),
        //     'subject' => '【Mops】パスワードリセットの申請を受け付けました',
        //     'body' => implode("\n", $lines),
        // ]);

        // MailMessageとして返す（通常送信用）
        return (new MailMessage)
            ->subject('【Mops】パスワードリセットの申請を受け付けました')
            ->from('eigyoukanri_mops@mii.maruho.co.jp', 'Mopsオンデマンド印刷発注システム')
            ->line($lines[0])
            ->line($lines[1])
            ->line($lines[2])
            ->action('パスワードを再設定する', $resetUrl)
            ->line($lines[4])
            ->line($lines[5])
            ->line($lines[6])
            ->line($lines[7])
            ->line($lines[8]);
    }
}
