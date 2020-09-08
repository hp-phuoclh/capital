<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProductController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/products",
     *   tags={"Product"},
     *   summary="Get list product",
     *   description="",
     *   operationId="products",
     *   @OA\Parameter(
     *      parameter="search_keyword",
     *      name="search_keyword",
     *      in="query",
     *      required=false,
     *      description="the key word search",
     *      example="sư",
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      parameter="category_id",
     *      name="category_id",
     *      in="query",
     *      required=false,
     *      description="the category id",
     *      @OA\Schema(
     *         type="integer",
     *         format="int32"
     *      ),
     *      example="1"
     *   ),
     *   @OA\Parameter(
     *      parameter="store_id",
     *      name="store_id",
     *      in="query",
     *      required=true,
     *      description="the store id",
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
     *                "products": "array({object})"
     *             }
     *     )
     *   )
     * )
     */
    public function index(Request $request)
    {
        $products = Product::isPublic()->with('category');
        
        if ($request->get("store_id")) {
            $products->join('store_products', 'store_products.product_id', 'products.id')
                ->where('store_products.store_id', '=', (int)$request->get("store_id"))
                ->selectRaw('products.*, store_products.price, store_products.sale_off');
            // $products->whereHas('stores', function($q) use ($request){

            //     $q->where('stores.id', '=', (int)$request->get("store_id"));
            
            // });
        }
        if ($request->get("search_keyword")) {
            $products = $products->where(function ($query) use ($request){
                            $query->whereRaw("LOWER(name) like ?", '%'.$request->get("search_keyword").'%');
                            $query->orWhereRaw("LOWER(description) like ?", '%'.$request->get("search_keyword").'%');
                        });
        }
        if ($request->get("category_id")) {
            $products->where("category_id", (int)$request->get("category_id"));
        }
        $response = $products->paginate(20);
        return response()->json($response);
    }

    /**
     * @OA\Get(path="/api/v1/products/{id}",
     *   tags={"Product"},
     *   summary="Get product",
     *   description="",
     *   operationId="product",
     *   @OA\Parameter(
     *      parameter="id",
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="the product id",
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
     *                "product": "{object}"
     *             }
     *     )
     *   )
     * )
     */
    public function detail($id)
    {
        $product = Product::with('category')->where('id', $id)->first();
        $response = [
            'code' => 200,
            'message' => '',
            'error' => '',
            'product' => $product
        ];

        if(!$product) {
            $response = [
                'code' => 404,
                'error' => 'Không tìm thấy thông tin sản phẩm.',
            ];
        }
        return response()->json($response);
    }
}
