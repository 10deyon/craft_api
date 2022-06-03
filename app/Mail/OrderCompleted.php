<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderCompleted extends Mailable
{
   use Queueable, SerializesModels;

   /**
    * The order instance.
    *
    * @var \App\Models\Order
    */
   public $order;

   /**
    * Create a new message instance.
    *
    * @param  \App\Models\Order  $order
    * @return void
    */
   public function __construct(Order $order)
   {
      Log::info($order);
      $this->order = $order;
   }

   /**
    * Build the message.
    *
    * @return $this
    */
   public function build()
   {
      return $this->view('emails.order_completed')->with([
         'orderName' => $this->order->name,
         'orderPrice' => $this->order->tagline,
      ]);
   }
}
