<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use DB;
use Exception;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Events\NewOrder;

class OrderController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/orders",
     *   tags={"Order"},
     *   summary="Get list order",
     *   description="",
     *   operationId="listOrders",
     *   @OA\Parameter(
     *      parameter="code",
     *      name="code",
     *      in="query",
     *      required=false,
     *      description="the code search",
     *      example="X2X4X6",
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      parameter="price_min",
     *      name="price_min",
     *      in="query",
     *      required=false,
     *      description="the price min",
     *      @OA\Schema(
     *         type="integer",
     *         format="int32"
     *      ),
     *      example="5000"
     *   ),
     *   @OA\Parameter(
     *      parameter="price_max",
     *      name="price_max",
     *      in="query",
     *      required=false,
     *      description="the price min",
     *      @OA\Schema(
     *         type="integer",
     *         format="int32"
     *      ),
     *      example="10000"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(
     *          example={
     *                "code": "",
     *                "message": "",
     *                "error": "",
     *                "orders": "array({object})"
     *             }
     *     )
     *   ),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     */
    public function index(Request $request)
    {
        $orders = Order::Auth()->with('products')->with('store');
        if ($request->get("code")) {
            $orders = $orders->where(function ($query) use ($request){
                            $query->whereRaw("LOWER(code) like ?", '%'.$request->get("code").'%');
                        });
        }
        if ($request->get("price_min")) {
            $orders->where("total", '>=', (int)$request->get("price_min"));
        }
        if ($request->get("price_max")) {
            $orders->where("total", '<=', (int)$request->get("price_max"));
        }
        $response = $orders->orderBy('created_at', 'DESC')->paginate(20);
        return response()->json($response);
    }

    /**
     * @OA\Post(path="/api/v1/orders",
     *   tags={"Order"},
     *   summary="store order",
     *   description="",
     *   operationId="store",
     *   @OA\Parameter(
     *      in="header",
     *      parameter="X-localization",
     *      name="X-localization",
     *      description="localization",
     *      @OA\Schema(
     *         type="string"
     *      ),
     *      example="en"
     *   ),
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               example={
     *                "phone": "0987654321",
     *                "address": "The Extremes of Good and Evil",
     *                "full_name": "Oly val",
     *                "store_id": "",
     *                "shipping_time": "20",
     *                "note": "is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic",
     *                "details": {
     *                    {
     *                      "quantity": 1,
     *                      "product_id": 1
     *                    },
     *                    {
     *                      "quantity": 2,
     *                      "product_id": 8,
     *                      "size": "M"
     *                    }
     *                  },
     *               }
     *           )
     *       )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(
     *          example={
     *                "code": "",
     *                "message": "",
     *                "error": "",
     *                "order": "{object}"
     *             }
     *     )
     *   ),
     *   @OA\Response(response=422, description="validate fails."),
     *   @OA\Response(response=401, description="unauthenticated."),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     */
    public function store(Request $request)
    {

        $response = [
            'code' => 200,
            'message' => '',
            'error' => '',
        ];

        $validator = Validator::make($request->all(), [
            '*.*.product_id' => 'required|integer|min:0',
            '*.*.quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            $response['code'] = 422;
            $response['error'] = $validator->errors();
            return response()->json($response, 422);
        }

        DB::beginTransaction();
        try {
            $order = new Order;
            $order->status = OrderStatus::AddNew;
            $order->user_id = Auth::id();
            // has phone
            if ($request->phone) {
                $order->phone = $request->phone;
            }

            if ($request->address) {
                $order->address = $request->address;
            }

            if ($request->full_name) {
                $order->full_name = $request->full_name;
            }

            if ($request->note) {
                $order->note = $request->note;
            }

            if ($request->store_id) {
                $order->store_id = $request->store_id;
            }

            if ($request->shipping_time) {
                $order->shipping_time = $request->shipping_time;
            }

            $order->total = 0;
            $order->save();
            // order detail
            foreach ($request->details as $detail) {
                // check has product
                $product = Product::find($detail['product_id']);
                if (!$product) {
                    $response['code'] = 404;
                    throw new Exception("Không tìm thấy sản phẩm với id là: " . $detail['product_id']);
                }

                $order->total += ($product->sale_off ?: $product->price) * $detail['quantity'];
                $detail['size'] = isset($detail['size']) ? $detail['size'] : '';
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'price' => $product->price,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'size' => $detail['size'],
                ]);
            }
            $order->save();
            // send notification
            $is_client = 1;
            event(new NewOrder($order,auth()->user(),$is_client));
            // commit db
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            \Log::error($e->getMessage());
            $response['code'] = 422;
            $response['error'] = $e->getMessage();
            return response()->json($response, 422);
        }
        $response['message'] = __("Order Successfully!");
        $response['order'] = $order;
        return response()->json($response);
    }

    /**
     * @OA\Get(path="/api/v1/orders/{id}",
     *   tags={"Order"},
     *   summary="Get order",
     *   description="",
     *   operationId="order",
     *   @OA\Parameter(
     *      parameter="id",
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="the order id",
     *      @OA\Schema(
     *         type="integer",
     *         format="int32"
     *      ),
     *      example="1"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(
     *          example={
     *                "code": "",
     *                "message": "",
     *                "error": "",
     *                "order": "{object}"
     *             }
     *     )
     *   ),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     * )
     */
    public function detail($id)
    {
        $order = Order::Auth()->with('products')->with('store')->where('id', $id)->first();
        $response = [
            'code' => 200,
            'message' => '',
            'error' => '',
            'order' => $order
        ];

        if(!$order) {
            $response = [
                'code' => 404,
                'error' => 'Không tìm thấy thông tin đơn hàng.',
            ];
        }
        return response()->json($response);
    }
}
