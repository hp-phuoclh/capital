<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Workflow;
use App\Enums\OrderStatus;
use BenSampo\Enum\Rules\EnumValue;
use Carbon\Carbon;
use DB;
use App\Events\NewOrder;
use App\Events\ChangeOrder;

class OrderController extends Controller
{
    /**
     * construct
     *
     * @return void
     */
    public function __construct() {
        // permission
        $this->middleware('permission:add orders')->only('create', 'store');
        $this->middleware('permission:edit orders')->only('edit', 'update');
        $this->middleware('permission:show orders')->only('show','index');
        $this->middleware('permission:delete orders')->only('destroy');
        $this->middleware('permission:refund orders')->only('refund','refund_update');

        // view share
        $users = User::orderBy('name')->get()->pluck('name', 'id');
        view()->share('users',$users);
        view()->share('status',OrderStatus::toSelectArray());
        view()->share('status_wf', array_flip(OrderStatus::toArray()));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get client category
        $orders = Order::with('products');

        if ($request->get("search_keyword")) {
            $orders = $orders->where(function ($query) use ($request){
                            $query->whereRaw("LOWER(code) like ?", '%'.$request->get("search_keyword").'%');
                            $query->orWhereHas('products', function ($query) use ($request)  {
                                $query->whereRaw("LOWER(name) like ?", '%'.$request->get("search_keyword").'%');
                            });
                        });
            $request->flash();
        }

        if($request->get("start_date") && $request->get("end_date")) {
            $start_date = Carbon::createFromFormat('d/m/Y', $request->get("start_date"))->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d/m/Y', $request->get("end_date"))->format('Y-m-d');
            $orders->whereRaw("(created_at >= ? AND created_at <= ?)", [$start_date." 00:00:00", $end_date." 23:59:59"]);
            $request->flash();
        }

        if ($request->get("user_id")) {
            $orders->where("user_id", (int)$request->get("user_id"));
            $request->flash();
        }
        if ($request->get("_status")) {
            $status = explode(',', $request->get("_status"));
            $orders->whereIn("status", $status);
            $request->flash();
        }
        $orders->orderBy('status');
        $orders->orderBy('created_at');
        return view("order.index", ["orders" => $orders->paginate()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("order.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check product
        if(!$request->products) {
            return redirect()->back()->with(['error' => __('No products have been selected!')]);
        }

        // if new address
        $rules = [
            'search_user' => 'required',
            'search_store' => 'required',
            'address' => 'required',
            'products.*' => 'required|numeric|min:0|not_in:0',
        ];
        $customMessages = [
            'search_user.required' => __('Required'),
            'search_store.required' => __('Required'),
            'address.required' => __('Required'),
            'products.*.required' => __('Quantity not allowed to be empty'),
            'products.*.numeric' => __('Quantity must be a number'),
            'products.*.min' => __('Quantity must be greater than zero'),
            'products.*.not_in' => __('Quantity must be greater than zero'),
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages)->validate();

        // find user
        $user = User::find($request->search_user);
        if(!$user) {
            return redirect()->back()->with(['error' => __('User does not exist')]);
        }

        // find store
        $store = Store::find($request->search_store);
        if(!$store) {
            return redirect()->back()->with(['error' => __('Store does not exist')]);
        }

        DB::beginTransaction();
        try {
            // insert order
            $order = new Order;
            $order->status = OrderStatus::AddNew;
            $order->user_id = $user->id;
            $order->phone = $user->phone;
            $order->address = $request->address;
            $order->full_name = $user->name;
            $order->note = $request->note;
            $order->store_id = $store->id;
            $order->shipping_time = $request->shipping_time ?:0;
            $order->total = 0;
            $order->save();

            // insert oder product
            foreach($request->products as $id => $qty) {
                $product = Product::find($id);
                if(!$product){
                    return redirect()->back()->with(['error' => __('Product not found')]);
                }
                $order_product = new OrderItem();
                $order_product->order_id = $order->id;
                $order_product->product_id = $product->id;
                $order_product->quantity = $qty;
                $order_product->price = $product->sale_off ? $product->sale_off : $product->price;
                $order_product->save();

                $order->total += ($product->sale_off ? $product->sale_off : $product->price)*$qty;
            }

            // insert quantity price total order
            $order->save();

            // send notification
            $is_client = 0;
            event(new NewOrder($order,$user,$is_client));

            // commit database
            DB::commit();
            // return
            return redirect()->back()->with(['success' => __('Created success!')]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => __('Error!')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view("order.view", ["order" => $order]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        // check status
        if($order->is_completed || $order->status == OrderStatus::Done) {
            return redirect()->back()->with(['error' => __('Cannot edit this order!')]);
        }
        return view("order.edit",["order" => $order]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $message = ['success' => __('Update successfully!')];
        if(isset($request->mode)) {
            switch ($request->mode) {
                case 'status':
                    $request->validate([
                        'status' => ['required', new EnumValue(OrderStatus::class, false)],
                    ]);
                    $workflow = Workflow::get($order);
                    $changStatus = OrderStatus::fromValue((int)$request->status);
                    if($workflow->can($order, $changStatus->key)) {
                        $workflow->apply($order, $changStatus->key);
                        $order->save();

                        // send notification
                        $is_client = 0;
                        $user = User::find($order->user_id);
                        event(new ChangeOrder($order,$user,$is_client));
                    } else {
                        $message = ['error' => __('Orders cannot be changed to').$changStatus->description];
                    }
                    break;
                default:
                    // check product
                    if(!$request->products) {
                        return redirect()->back()->with(['error' => __('No products have been selected!')]);
                    }

                    // check status
                    if($order->is_completed || $order->status == OrderStatus::Done) {
                        return redirect()->back()->with(['error' => __('Cannot edit this order!')]);
                    }

                    // if new address
                    $rules = [
                        'search_user' => 'required',
                        'search_store' => 'required',
                        'address' => 'required',
                        'products.*' => 'required|numeric|min:0|not_in:0',
                    ];
                    $customMessages = [
                        'search_user.required' => __('Required'),
                        'search_store.required' => __('Required'),
                        'address.required' => __('Required'),
                        'products.*.required' => __('Quantity not allowed to be empty'),
                        'products.*.numeric' => __('Quantity must be a number'),
                        'products.*.min' => __('Quantity must be greater than zero'),
                        'products.*.not_in' => __('Quantity must be greater than zero'),
                    ];

                    $validator = Validator::make($request->all(), $rules, $customMessages)->validate();

                    // find user
                    $user = User::find($request->search_user);
                    if(!$user) {
                        return redirect()->back()->with(['error' => __('User does not exist')]);
                    }

                    // find store
                    $store = Store::find($request->search_store);
                    if(!$store) {
                        return redirect()->back()->with(['error' => __('Store does not exist')]);
                    }

                    DB::beginTransaction();
                    try {
                        // insert order
                        $order->user_id = $user->id;
                        $order->phone = $user->phone;
                        $order->address = $request->address;
                        $order->full_name = $user->name;
                        $order->note = $request->note;
                        $order->store_id = $store->id;
                        $order->total = 0;
                        $order->save();

                        // delete order item old
                        OrderItem::where('order_id',$order->id)->forceDelete();

                        // insert oder product
                        foreach($request->products as $id => $qty) {
                            $product = Product::find($id);
                            if(!$product){
                                return redirect()->back()->with(['error' => __('Product not found')]);
                            }
                            $order_product = new OrderItem();
                            $order_product->order_id = $order->id;
                            $order_product->product_id = $product->id;
                            $order_product->quantity = $qty;
                            $order_product->price = $product->sale_off ? $product->sale_off : $product->price;
                            $order_product->save();

                            $order->total += ($product->sale_off ? $product->sale_off : $product->price)*$qty;
                        }

                        // insert quantity price total order
                        $order->save();

                        // send notification
                        $is_client = 0;
                        event(new ChangeOrder($order,$user,$is_client));

                        // commit database
                        DB::commit();
                        // return
                        return redirect()->back()->with(['success' => __('Update successfully!')]);
                    } catch (Exception $e) {
                        DB::rollBack();
                        return redirect()->back()->with(['error' => __('Error!')]);
                    }
                    break;
            }
        }

        return redirect()->back()
            ->with($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * Get user.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_user(Request $request)
    {
        $users = Null;
        $keyword = $request->keyword;
        if($keyword) {
            $users = User::select('users.*')
                       ->where('users.name','LIKE','%'.$keyword.'%')
                       ->orWhere('users.phone','LIKE','%'.$keyword.'%')
                       ->orWhere('users.email','LIKE','%'.$keyword.'%')->take(10)->get();
        }
        $items = [];
        if($users) {
            foreach($users as $user) {
                $items[] = [
                    "id" => $user->id,
                    "text" => $user->name.' - '.$user->phone.' - '.$user->email,
                ];
            }
        }

        $result = [
            'items' => $items,
        ];

		return json_encode($result);
    }

     /**
     * Get store.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_store(Request $request)
    {
        $users = Null;
        $keyword = $request->keyword;
        if($keyword) {
            $users = Store::select('stores.*')
                       ->where('stores.name','LIKE','%'.$keyword.'%')
                       ->orWhere('stores.phone','LIKE','%'.$keyword.'%')
                       ->orWhere('stores.email','LIKE','%'.$keyword.'%')
                       ->orWhere('stores.address','LIKE','%'.$keyword.'%')->take(10)->get();
        }
        $items = [];
        if($users) {
            foreach($users as $user) {
                $items[] = [
                    "id" => $user->id,
                    "text" => $user->name.' - '.$user->phone.' - '.$user->email.' - '.$user->address,
                ];
            }
        }

        $result = [
            'items' => $items,
        ];

		return json_encode($result);
    }

    /**
     * Get product add oder.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_product(Request $request)
    {
        $products = Null;
        $keyword = $request->keyword;
        if($keyword) {
            $products = Product::where('name','LIKE','%'.$keyword.'%')
                                ->orWhere('code','LIKE','%'.$keyword.'%')
                                ->orWhere('price','LIKE','%'.$keyword.'%')
                                ->orWhere('sale_off','LIKE','%'.$keyword.'%')->take(10)->get();
        }

        $result = [
            'items' => $products,
        ];

		return json_encode($result);
    }

    /**
     * Get product add oder.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_product_by_id(Request $request)
    {
        $id = $request->id;
        $product = Product::find($id);

        if(!$product) {
            $result = [
                'status' => 'failed',
                'message' => __('Product not found'),
            ];
            return json_encode($result);
        }

        $result = [
            'status' => 'success',
            'message' => __('Success!'),
            'data' => [
                'product' => $product,
            ],
        ];

		return json_encode($result);
    }

    /**
     * Display page refund
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function refund($id)
    {
        // find order
        $order = Order::find($id);
        if(!$order) {
            return redirect()->back()->with(['error' => __('Order does not exist')]);
        }

        // check paid_flg
        if(!$order->paid_flg) {
            return redirect()->back()->with(['error' => __('Unpaid order')]);
        }

        return view("order.refund",["order" => $order]);
    }

    /**
     * Update refund order
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function refund_update(Request $request, $id)
    {
        // find order
        $order = Order::find($id);
        if(!$order) {
            return redirect()->back()->with(['error' => __('Order does not exist')]);
        }

        // check paid_flg
        if(!$order->paid_flg) {
            return redirect()->back()->with(['error' => __('Unpaid order')]);
        }

        $request->validate([
            'refund' => 'required|numeric',
        ]);

        if($request->refund > $order->total) {
            return redirect()->back()->with(['error' => __('Refund amount cannot be bigger than total amount')]);
        }

        DB::beginTransaction();
        try {
            $order->refund = $request->refund;
            $order->save();
            // commit database
            DB::commit();
            // return
            return redirect()->back()->with(['success' => __('Update successfully!')]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => __('Error!')]);
        }
    }
}
