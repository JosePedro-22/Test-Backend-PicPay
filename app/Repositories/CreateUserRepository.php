<?php

namespace App\Repositories;

use App\Models\Retailer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\InvalidDataProviderException;
use PHPUnit\Logging\Exception;

class CreateUserRepository
{
    public function createUser($request, string $provider): JsonResponse
    {
        $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'cpf' => 'required',
                'password' => 'required'
            ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

//        $selectedTypeUserProvider = $this->getProvider($request, $provider);
        $this->getProvider($request, $provider);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
//            'token' => $selectedTypeUserProvider->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
    public function getProvider($request, string $provider): Exception | User | Retailer
    {
        if($provider === 'user') {
            return User::create([
                'name' => $request->name,
                'cpf' => $request->cpf,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
        }
        else if ($provider === 'retailer') {
            return Retailer::create([
                'name' => $request->name,
                'cpf' => $request->cpf,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
        }
        else return throw new InvalidDataProviderException('Wrong provider provided');
    }
}
