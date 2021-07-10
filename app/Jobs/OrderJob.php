<?php

namespace App\Jobs;

use App\ActiveStatusHistory;
use App\QualifiedStatusHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Order;
use App\EmailTemplate;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        Order::sendToShipStation($this->order);
        EmailTemplate::sendOrderConfirmationEmail($this->order);
        EmailTemplate::sendSponsorNotificationEmail($this->order);
//        ActiveStatusHistory::userActiveStatusUpdate($this->order->user_id);
//        QualifiedStatusHistory::userQualifiedStatusUpdate($this->order->user_id);
    }
}
