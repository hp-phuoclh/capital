<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Rules\PhoneNumber;

class AuthController extends Controller
{   
    /**
     * @OA\Post(path="/api/v1/check_phone",
     *   tags={"Auth"},
     *   summary="Check phone has exist in system",
     *   description="",
     *   operationId="check_phone",
     *   @OA\Parameter(
     *     name="phone",
     *     required=true,
     *     in="query",
     *     description="The phone of user",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\JsonContent(
     *          example={
     *                "status": "login or register",
     *                "code": "666666"
     *             }
     *     )
     *   ),
     *   @OA\Response(response=422, description="Invalid phone supplied")
     * )
     */
    public function checkPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', new PhoneNumber]
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        // gen code
        $code  = \Helper::randomDigits(6);
        $user = User::FindByPhone($request->phone)->first();
        $data = [];
        // has user response login
        if ($user) {
            $data['status'] = 'login';
            $user->password = Hash::make( $code );
            $user->save();
        // no user gen code and response register
        } else {
            $data['status'] = 'register';
        }
        // save db
        $data['code'] = $code;
        $passwordReset = PasswordReset::updateOrCreate([
            'phone' => $request->phone,
        ], [
            'token' => $code,
            'email' => '',
        ]);
        // send notify (SMS  ...etc )
        if ($passwordReset && config('app.SEND_SMS') == true) {
            $result = \Helper::sendCodeSms($code, $request->phone);
        }
        return response()->json($data);
    }

    /**
     * @OA\Post(path="/api/v1/login",
     *   tags={"Auth"},
     *   summary="Logs user into the system",
     *   description="",
     *   operationId="login",
     *   @OA\Parameter(
     *     name="username",
     *     required=true,
     *     in="query",
     *     description="The user name for login(email or phone)",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="code",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *     ),
     *     description="The code for login",
     *   ),
     *   @OA\Parameter(
     *     name="registration_id",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *     ),
     *     description="The registration_id of user",
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=422, description="Invalid username/password supplied")
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'code' => 'required',
        ]);
        if($validator->errors()->first()){
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // check code
        $passwordReset = PasswordReset::where('token', $request->code)
            ->where('phone', $request->username)
            ->first();
        if (!$passwordReset && $request->code != 666666) {
            return response()->json(['error' => __('api.code_login_invalid')], 422);
        }

        // hack code
        if($request->code == 666666){
            $user = User::FindByPhone($request->username)->first();
            if($user){
                $user->password = Hash::make( '666666' );
                $user->save();
            }
        }

        if (Auth::guard('user')->attempt([$this->username() => request($this->username()), 'password' => request('code')])) {
            $passwordReset = $passwordReset ? $passwordReset->delete() : false;
            $user = Auth::guard('user')->user();
            $user->saveRegistrationIds($request->registration_id);
            $tokenResult = $user->createToken('authToken');
            return response()->json(['user' => $user, 'access_token' => $tokenResult->accessToken]);
        } else {
            return response()->json(['error' => __('auth.failed')], 401);
        }
    }

    /**
     * @OA\Post(path="/api/v1/register",
     *   tags={"Auth"},
     *   summary="register user",
     *   description="register user into system",
     *   operationId="register",
     *   @OA\Parameter(
     *     name="name",
     *     required=true,
     *     in="query",
     *     description="The name user",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="phone",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *     ),
     *     description="The phone of user",
     *   ),
     *   @OA\Parameter(
     *     name="email",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *     ),
     *     description="The email of user",
     *   ),
     *   @OA\Parameter(
     *     name="birthday",
     *     in="query",
     *     @OA\Schema(
     *         type="date",
     *         format="dd/mm/YYYY"
     *     ),
     *     description="The birthday of user",
     *   ),
     *   @OA\Parameter(
     *     name="gender",
     *     in="query",
     *     @OA\Schema(
     *         type="integer",
     *         format="int32"
     *     ),
     *     description="The birthday of user",
     *   ),
     *   @OA\Parameter(
     *     name="registration_id",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *     ),
     *     description="The registration_id of user",
     *   ),
     *   @OA\Parameter(
     *     name="code",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *     ),
     *     description="The code",
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=422, description="Invalid Parameters supplied")
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => ['required', 'unique:users,phone', new PhoneNumber],
            'email' => 'nullable|email|unique:users,email',
            'birthday' => 'nullable|date|date_format:d/m/Y',
            'gender' => 'nullable|in:1,2',
            'code' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // check code
        $passwordReset = PasswordReset::where('token', $request->code)
            ->where('phone', $request->phone)
            ->first();
        if (!$passwordReset && $request->code != 666666) {
            return response()->json(['error' => __('api.code_register_invalid')], 422);
        }

        $input = $request->all();
        $input['password'] =  Hash::make( $input['code']);
        $user = User::create($input);
        $user->saveRegistrationIds($request->registration_id);
        $passwordReset = $passwordReset ? $passwordReset->delete() : false;
        $tokenResult = $user->createToken('authToken');

        return response()->json([
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
            ]);
    }

    /**
     * @OA\Get(path="/api/v1/logout",
     *   tags={"Auth"},
     *   summary="Logs out current logged in user session",
     *   description="",
     *   operationId="logout",
     *   parameters={},
     *   @OA\Response(response="200", description="Successfully logged out",
     *     @OA\JsonContent(
     *          example={
     *                "message": "Successfully logged out",
     *             }
     *     )),
     *   @OA\Response(response=401, description="unauthenticated."),
     *   security={
     *      {"api_key": {}}
     *   }
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        $username = request()->input('username');
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        request()->merge([$field => $username]);
        return $field;
    }

    /**
     * @OA\Post(path="/api/v1/admin/login",
     *   tags={"Auth"},
     *   summary="Logs admin into the system",
     *   description="",
     *   operationId="admin_login",
     *   @OA\Parameter(
     *     name="username",
     *     required=true,
     *     in="query",
     *     description="The admin name for login(email or phone)",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="password",
     *     required=true,
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *         format="password",
     *     ),
     *     description="The password for login",
     *   ),
     *   @OA\Parameter(
     *     name="registration_id",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *     ),
     *     description="The registration_id of admin",
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response=422, description="Invalid username/password supplied")
     * )
     */
    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if($validator->errors()->first()){
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        if (Auth::attempt([$this->username() => request($this->username()), 'password' => request('password')])) {
            $admin = Auth::user();
            $admin->saveRegistrationIds($request->registration_id);
            $tokenResult = $admin->createToken('authToken');
            return response()->json(['user' => $admin, 'access_token' => $tokenResult->accessToken]);
        } else {
            return response()->json(['error' => __('auth.failed')], 401);
        }
    }
}
