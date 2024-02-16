<?php

namespace App\Repositories;

use App\Models\Retailer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Logging\Exception;

class AuthRepository
{

    public function authenticate(string $provider, Request $request): JsonResponse
    {
        $providers = [
            'user',
            'retailer'
        ];

        if (!in_array($provider, $providers))
            return response()->json(['errors' => ['main' => 'Wrong provider provided']], 422);

        $selectedProvider = $this->getProvider($provider);

        $model = $selectedProvider->where('email', $request->input('email'))->first();

        if(!$model)
            return response()->json(['errors' => ['main' => 'Wrong provider provided']], 401);

        if(!Hash::check($request->input('password'), $model->password))
            return response()->json(['errors' => ['main' => 'Wrong Credentials']], 401);

        $token = $model->createToken($provider);

        return $token;
    }

    public function getProvider(string $provider): User | Retailer | Exception
    {
        if($provider === 'user')
            return new User();
        else if($provider === 'provider')
            return new Retailer();
        else
            return throw new Exception('Provider not found');
    }
}
