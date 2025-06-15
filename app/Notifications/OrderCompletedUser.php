<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\GeneralClass;

class OrderCompletedUser extends Notification
{
    use Queueable;

    protected $orderCode;
    protected $orderDatetime;
    protected $orderName;
    protected $deliveryName;
    protected $deliveryAddress;
    protected $deliveryTel;
    protected $cartItems;
    protected $total;

    public function __construct($orderCode, $orderDatetime, $orderName, $deliveryName, $deliveryAddress, $deliveryTel, $cartItems, $total)
    {
        $this->orderCode = $orderCode;
        $this->orderDatetime = $orderDatetime;
        $this->orderName = $orderName;
        $this->deliveryName = $deliveryName;
        $this->deliveryAddress = $deliveryAddress;
        $this->deliveryTel = $deliveryTel;
        $this->cartItems = $cartItems;
        $this->total = $total;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->from('eigyoukanri_mops@mii.maruho.co.jp', 'Mopsオンデマンド印刷発注システム')
            ->subject("【Mops】ご注文ありがとうございます（注文番号：{$this->orderCode}）")
            ->greeting("{$this->orderName} 様")
            ->line("このたびはご注文いただき、誠にありがとうございます。")
            ->line("以下の内容で注文を受け付けました。")
            ->line('------------------------------')
            ->line("■ご注文番号：{$this->orderCode}")
            ->line("■ご注文日時：{$this->orderDatetime}")
            ->line("■お届け先名称：{$this->deliveryName}")
            ->line("■お届け先住所：{$this->deliveryAddress}")
            ->line("■お届け先電話番号：{$this->deliveryTel}")
            ->line("【ご注文内容】");

        foreach ($this->cartItems as $item) {
            $unitValue = '';
            if (!empty($item['tool']->UNIT_TYPE)) {
                $unitRecord = GeneralClass::where('TYPE_CODE', 'UNIT_TYPE')
                    ->where('KEY', $item['tool']->UNIT_TYPE)
                    ->first();
                $unitValue = $unitRecord?->VALUE ?? '';
            }

            $mail->line("ツールコード：{$item['tool']->TOOL_CODE}");
            $mail->line("ツール名：{$item['tool']->TOOL_NAME}");
            $mail->line("数量：{$item['QUANTITY']}{$unitValue}");
            $mail->line("単価：" . number_format($item['tool']->TANKA ?? 0) . "円");
            $mail->line("金額：" . number_format($item['subtotal']) . "円\n");
            $mail->line("\n");
        }

        $mail->line("■合計金額：" . number_format($this->total) . "円\n")
            ->line("\n")
            ->line("※このメールは自動送信されています。")
            ->line("内容にお心当たりがない場合は、お手数ですが下記までご連絡ください。")
            ->line('------------------------------')
            ->line("【問い合わせ窓口】")
            ->line("マルホ株式会社：eigyoukanri_mops@mii.maruho.co.jp");

        return $mail;
    }
}
