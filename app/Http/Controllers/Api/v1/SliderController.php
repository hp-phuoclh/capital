<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Slider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class SliderController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/sliders",
     *   tags={"Slider"},
     *   summary="Get list slider",
     *   description="",
     *   operationId="sliders",
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(
     *          example={
     *                "code": "",
     *                "message": "",
     *                "error": "",
     *                "sliders": "array({object})"
     *             }
     *     )
     *   )
     * )
     */
    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        $response = [
            'code' => 200,
            'message' => '',
            'error' => '',
            'sliders' => $sliders
        ];
        return response()->json($response);
    }
}
