<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginRepository
{

    public function login(Request $request): JsonResponse
    {
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validateUser->fails())
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = User::where('email', $request['email'])->first();
            $accessToken = $user->createToken("API TOKEN")->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $accessToken
            ], 200);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
            ], 401);
        }
    }
}
