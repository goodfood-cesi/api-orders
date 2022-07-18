<?php

namespace App\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmed extends Mailable {
    use Queueable, SerializesModels;

    public object $user;

    public object $order;

    public function __construct($user, $order) {
        $this->user = $user;
        $this->order = $order;
    }

    public function build(): OrderConfirmed {
        return $this
            ->markdown('emails.orderconfirmed', ['user' => $this->user, 'order' => $this->order])
            ->subject('GoodFood - Order confirmed (#' . $this->order->id . ')');
    }
}
