<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @OA\Get(path="/api/v1/account/details",
     *   tags={"User"},
     *   summary="Get info of user",
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

    /**
     * @OA\Put(path="/api/v1/account/update",
     *   tags={"User"},
     *   summary="update user",
     *   description="",
     *   operationId="update",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               example={
     *                "name": "my name",
     *                "email": "my-email@e-mail.com",
     *                "birthday": "dd/mm/yyyy",
     *                "gender": "1 or 2 -- 1: Female, 2: Male",
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
     *                "user": "{object}"
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
    public function update(Request $request)
    {

        $response = [
            'code' => 200,
            'message' => '',
            'error' => '',
        ];
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email',
            'birthday' => 'nullable|date|date_format:d/m/Y',
            'gender' => 'nullable|in:1,2',
        ]);

        if ($validator->fails()) {
            $response['code'] = 422;
            $response['error'] = $validator->errors();
            return response()->json($response, 422);
        }

        try {
            // has name
            if ($request->name) {
                $user->name = $request->name;
            }

            if ($request->email) {
                $user->email = $request->email;
            }

            if ($request->birthday) {
                $user->birthday = $request->birthday;
            }

            if ($request->gender) {
                $user->gender = $request->gender;
            }
            $user->save();
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'System Error'], 422);
        }
        $response['message'] = 'ok';
        return response()->json($response);
    }
}
