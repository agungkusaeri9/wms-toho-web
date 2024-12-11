<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     *
     * @OA\Info(title="Auth API", version="1.0")
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Login User",
     *     description="Login and get a JWT token",
     *     operationId="login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="yourpassword")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="your_access_token"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid credentials",
     *     )
     * )
     */
    public function login()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::validationError($validator->errors());
        }

        try {
            $credentials = request(['email', 'password']);
            // dd('ok');
            if (! $token = auth('api')->attempt($credentials)) {
                return ResponseFormatter::error([], 'Email atau password salah', 401);
            }

            return $this->respondWithToken($token);
        } catch (\Throwable $th) {
            //throw $th;
            return ResponseFormatter::error([], $th->getMessage(), 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @OA\Get(
     *     path="/api/v1/me",
     *     summary="Get Authenticated User",
     *     description="Get the details of the authenticated user",
     *     operationId="me",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="avatar", type="string", example="avatar.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Not logged in"
     *     )
     * )
     */
    public function me()
    {
        return ResponseFormatter::success(auth()->user(),'User detail',200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="Logout User",
     *     description="Logout and invalidate the current JWT token",
     *     operationId="logout",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @OA\Post(
     *     path="/api/v1/refresh",
     *     summary="Refresh Token",
     *     description="Refresh the JWT token",
     *     operationId="refresh",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully refreshed token",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="new_access_token"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600)
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return ResponseFormatter::success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * env('JWT_TTL')
        ]);
    }
}
