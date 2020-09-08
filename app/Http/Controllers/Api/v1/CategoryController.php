<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class CategoryController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/categories",
     *   tags={"Category"},
     *   summary="Get list category",
     *   description="",
     *   operationId="categories",
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(
     *          example={
     *                "code": "",
     *                "message": "",
     *                "error": "",
     *                "categories": "array({object})"
     *             }
     *     )
     *   )
     * )
     */
    public function index()
    {
        $categories = Category::with('parent')->orderBy('order')->get();
        $response = [
            'code' => 200,
            'message' => '',
            'error' => '',
            'categories' => $categories
        ];
        return response()->json($response);
    }

    /**
     * @OA\Get(path="/api/v1/categories/{id}",
     *   tags={"Category"},
     *   summary="Get category",
     *   description="",
     *   operationId="category",
     *   @OA\Parameter(
     *      parameter="id",
     *      name="id",
     *      in="path",
     *      required=true,
     *      description="the category id",
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
     *                "category": "{object}"
     *             }
     *     )
     *   )
     * )
     */
    public function detail($id)
    {
        $category = Category::with('products')->where('id', $id)->first();
        $response = [
            'code' => 200,
            'message' => '',
            'error' => '',
            'category' => $category
        ];

        if(!$category) {
            $response = [
                'code' => 404,
                'error' => 'Không tìm thấy thông tin danh mục.',
            ];
        }
        return response()->json($response);
    }
}
