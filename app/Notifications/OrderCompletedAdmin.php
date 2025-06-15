<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\GeneralClass;

class OrderCompletedAdmin extends Notification
{
    use Queueable;

    protected $orderCode;
    protected $orderDatetime;
    protected $iraisakiName;
    protected $orderName;
    protected $iraisakiAddress;
    protected $iraisakiTel;
    protected $deliveryName;
    protected $deliveryAddress;
    protected $deliveryTel;
    protected $note;
    protected $cartItems;
    protected $total;

    public function __construct($orderCode, $orderDatetime, $iraisakiName, $orderName, $iraisakiAddress, $iraisakiTel, $deliveryName, $deliveryAddress, $deliveryTel, $note, $cartItems, $total)
    {
        $this->orderCode = $orderCode;
        $this->orderDatetime = $orderDatetime;
        $this->iraisakiName = $iraisakiName;
        $this->orderName = $orderName;
        $this->iraisakiAddress = $iraisakiAddress;
        $this->iraisakiTel = $iraisakiTel;
        $this->deliveryName = $deliveryName;
        $this->deliveryAddress = $deliveryAddress;
        $this->deliveryTel = $deliveryTel;
        $this->note = $note;
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
            ->subject("【Mops】注文を受け付けました（注文番号：{$this->orderCode}）")
            ->greeting("以下の内容で注文を受け付けました。")
            ->line('------------------------------')
            ->line("■注文番号：{$this->orderCode}")
            ->line("■注文日時：{$this->orderDatetime}")
            ->line("■依頼主名：{$this->iraisakiName}")
            ->line("■注文者　：{$this->orderName}")
            ->line("■依頼主住所：{$this->iraisakiAddress}")
            ->line("■依頼主電話番号：{$this->iraisakiTel}")
            ->line("■届け先名称：{$this->deliveryName}")
            ->line("■配送先住所：{$this->deliveryAddress}")
            ->line("■配送先電話番号：{$this->deliveryTel}")
            ->line("■備考：{$this->note}\n")
            ->line("\n")
            ->line("■注文内容");

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
            ->line("本注文の処理をお願いいたします。")
            ->line("詳細は管理画面よりご確認ください。")
            ->line('------------------------------')
            ->line("【問い合わせ窓口】")
            ->line("マルホ株式会社：eigyoukanri_mops@mii.maruho.co.jp");

        return $mail;
    }
}
