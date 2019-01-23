<?php

namespace Jiri\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jiri\Http\Controllers\Controller;
use Jiri\User;

class AuthController extends Controller
{
    
    /**
     * Create an admin user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string',
            'email'                 => 'required|string|email|unique:users',
            'password'              => 'required|string|confirmed',
            'password_confirmation' => 'required'
        ]);
        
        $user = new User([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'is_active' => 1,
            'is_admin'  => 1
        ]);
        
        $user->save();
        
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
    
    
    /**
     * Login with API
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean'
        ]);
        
        $credentials = request(['email', 'password']);
        
        if ( ! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 201);
        }
        
        $user = $request->user();
        
        $tokenResult = $user->createToken('Personal Access Token');
        $token       = $tokenResult->token;
        
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeek(1);
        }
        
        $token->save();
        
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
        
    }
    
    /**
     * Logout user (Revoke the token)
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    
    
    /**
     * Get the authenticated User
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    
    
}
