<?php

namespace App\Listeners;

use App\Events\NewOrder;
use App\Helpers\NotificationAdminAPI;
use App\Helpers\NotificationAPI;
use App\Models\Admin;
use App\Notifications\OrderCreate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\Middleware\Language;
use Illuminate\Foundation\Application;

class SendNewOrderNotification
{
    use InteractsWithQueue;

    private $app;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle the event.
     *
     * @param  NewOrder  $event
     * @return void
     */
    public function handle(NewOrder $event)
    {
        // get data
        $lang = $event->lang;
        $this->app->setLocale($lang);

        $order = $event->order;
        $user = $event->user;
        $is_client = $event->is_client;
        $order->shipping_time = $order->shipping_time ?: 15;

        try{
            // save database
            $user->notify(new OrderCreate($order));

            if($is_client) {
                //send all admin
                $admins = Admin::role('super-admin')->get();
                // add admin of store
                \Notification::send($admins, new OrderCreate($order));
            }
        } catch (\Exception $e) {
            \Log::error("Exception Event SendNewOrderNotification");
            \Log::error($e->getMessage());
        }

        // notify to user app
        $data_message = ['order_code' => $order->code, 'shipping_time' => $order->shipping_time];
        $content = __('notify new order user', $data_message);
        $notifyData = [
            'title' => __("Order Successfully!"),
            'content' => $content,
        ];
        NotificationAPI::send_notification_for_app($notifyData, $user->registration_ids);
        // notify to admin app
        // $content = __("notify new order admin", $data_message);
        // $notifyData = [
        //     'title' => __("Have new order!"),
        //     'content' => $content,
        //     'url' => route('orders.show', ['order' => $order->id]),
        // ];
        // NotificationAdminAPI::send_notification_for_app($notifyData, '', $order->id);
    }
}
