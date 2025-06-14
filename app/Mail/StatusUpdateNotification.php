<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $toolCodes;
    public $statusLabel;
    public $datetime;

    public function __construct($toolCodes, $status)
    {
        $this->toolCodes   = $toolCodes;
        $this->statusLabel = ['表示','仮登録','マルホ確認済み','中島準備完了','非表示'][$status];
        $this->datetime    = now()->format('Y/m/d H:i:s');
    }

//     public function build()
//     {
//         return $this->from('eigyoukanri_mops@mii.maruho.co.jp', 'Mopsオンデマンド印刷発注システム')
//                     ->to(['eigyoukanri_mops@mii.maruho.co.jp', 'mops-info@n-kobundo.co.jp'])
//                     ->subject('【Mops】ステータス変更通知（マルホ確認済）')
//                     ->view('emails.status_update')
//                     ->with([
//                         'toolCodes'   => $this->toolCodes,
//                         'statusLabel' => $this->statusLabel,
//                         'datetime'    => $this->datetime,
//                     ]);
//     }
// }
