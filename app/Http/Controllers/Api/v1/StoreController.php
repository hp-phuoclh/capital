<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class StoreController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/stores",
     *   tags={"Store"},
     *   summary="Get list store",
     *   description="",
     *   operationId="stores",
     *   @OA\Parameter(
     *      parameter="lat",
     *      name="lat",
     *      in="query",
     *      description="the store lat",
     *      @OA\Schema(
     *         type="string",
     *      ),
     *      example="10.854707"
     *   ),
     *   @OA\Parameter(
     *      parameter="long",
     *      name="long",
     *      in="query",
     *      description="the store long",
     *      @OA\Schema(
     *         type="string",
     *      ),
     *      example="106.628690"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(
     *          example={
     *                "code": "",
     *                "message": "",
     *                "error": "",
     *                "stores": "array({object})"
     *             }
     *     )
     *   )
     * )
     */
    public function index(Request $request)
    {
        $stores = null;
        if(isset($request->lat) && isset($request->long)) {
            $locationRaw = "ROUND(ACOS(COS( RADIANS( ? ))
                * COS( RADIANS( `lat` ))
                * COS( RADIANS( `long` ) - RADIANS( ? ))
                + SIN( RADIANS( ?  ))
                * SIN( RADIANS( `lat` ))
                ) * 6371, 2)";
            $stores = Store::selectRaw("* , $locationRaw AS distance_in_km", array($request->lat, $request->long, $request->lat))
                ->orderBy('distance_in_km', 'ASC')
                ->get();
        } else {
            $stores = Store::all();
        }
        $response = [
            'code' => 200,
            'message' => '',
            'error' => '',
            'stores' => $stores
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(path="/api/v1/stores/{id}",
     *   tags={"Store"},
     *   summary="Get store",
     *   description="",
     *   operationId="store",
     *   @OA\Parameter(
     *      parameter="id",
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="the store id",
     *      @OA\Schema(
     *         type="integer",
     *         format="int32"
     *      ),
     *      example="1"
     *   ),
     * 
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(
     *          example={
     *                "code": "",
     *                "message": "",
     *                "error": "",
     *                "store": "{object}"
     *             }
     *     )
     *   )
     * )
     */
    public function detail($id)
    {
        $store = Store::where('id', $id)->first();
        $response = [
            'code' => 200,
            'message' => '',
            'error' => '',
            'store' => $store
        ];

        if(!$store) {
            $response = [
                'code' => 404,
                'error' => 'Không tìm thấy thông tin danh mục.',
            ];
        }
        return response()->json($response);
    }
}
