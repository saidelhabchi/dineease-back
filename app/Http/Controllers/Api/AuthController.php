<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        try{
            $validateUser = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    "status" => false,
                    "message" => "validation failed",
                    "errors" => $validateUser->errors()
                    ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return response()->json([
                "status" => true,
                "message" => "user created successfully"
            ], 201);
        }catch(\Throwable $th){
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ],500);
        }
    }
    public function login(Request $request){
        try{
            $validateUser = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    "status" => false,
                    "message" => "validation failed",
                    "errors" => $validateUser->errors()
                    ], 401);
            }
            if(!Auth::attempt($request->only(['email','password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email',$request->email)->first();
            $accessToken = $user->createToken("access_token",[config('sanctum.expiration')])->plainTextToken;
            $refreshToken = $user->createToken("refresh_token",[config('sanctum.rt_expiration')])->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'user logged in successfully',
                'token' => $accessToken,
                'refresh_token' => $refreshToken,
            ]);

        }catch(\Throwable $th){
            return response()->json([
                "status" => false,
                "message" => $th->getMessage()
            ],500);
        }
    }
    public function refreshToken(Request $request){
        try{
            $accessToken = $request->user()->createToken('access_token',[config('sanctum.expiration')]);
            return response()->json([
                'status' => false,
                'token' => $accessToken,
            ],200);
        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }
}
