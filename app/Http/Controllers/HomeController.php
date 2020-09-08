<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $counts = [
            'orders' => 10,
            'products' => 99,
            'users' => 100,
            'orderItems' => 20,
        ];
        $totalAll = number_format(9999999);
        return view('home', [
            'totalAll' => $totalAll,
            'counts' => $counts
        ]);
    }

    /**
     * get notifications of admin
     *
     * @return \Illuminate\Http\Response
     */
    public function notifications(Request $request)
    {
        $notifications = auth()->user()->notifications()->paginate();
        if($request->ajax() && !$notifications->items()) {
            return response()->json(""); ;
        }
        return  view('shared.notify', [
                'notifications' => $notifications
            ]);
    }

    /**
     * get notifications of admin
     *
     * @return \Illuminate\Http\Response
     */
    public function notify(Request $request, $id)
    {
        $types = \Helper::notificationTypes();
        $notify = auth()->user()->notifications()->where('id', $id)->first();
        if($request->ajax() && !$notify) {
            return response()->json("");
        }
        if($notify->type == $types['orderCreate']) {
            return  view('templates.order_new', [
                'notify' => $notify,
                'new' => $request->new ?: false
            ]);
        }
    }

    /**
     * get notifications of admin
     *
     * @return \Illuminate\Http\Response
     */
    public function readAllNotification()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json("");
    }
}
