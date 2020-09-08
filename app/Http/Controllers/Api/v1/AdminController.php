<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Admin;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/admin/details",
     *   tags={"Admin"},
     *   summary="Get info of admin",
     *   description="",
     *   operationId="details",
     * 
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(
     *          example={
     *                "code": "",
     *                "message": "",
     *                "error": "",
     *                "user": "{object}"
     *             }
     *     )
     *   ),
     *   @OA\Response(response=401, description="unauthenticated."),
     *     security={
     *       {"api_key": {}}
     *     }
     * )
     */
    public function details()
    {
        $user = Auth::user();
        $response = [
            'code' => 200,
            'message' => '',
            'error' => '',
            'user' => $user
        ];
        return response()->json($response);
    }
}
