<?php

namespace App\Repositories;

use App\Models\Retailer;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\InvalidDataProviderException;
use PHPUnit\Logging\Exception;

class AuthRepository
{
    /**
     * @throws AuthenticationException
     */
    public function authenticate(string $provider, Request $request): JsonResponse
    {

        $selectedProvider = $this->getProvider($provider);

        $model = $selectedProvider->where('email', $request->input('email'))->first();

        if(!$model)
            throw new InvalidDataProviderException('Wrong provider provided');

        if(!Hash::check($request->input('password'), $model->password))
            throw new AuthenticationException('Wrong Credentials');

        $token = $model->createToken($provider);

        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => $token->expires_at ?? '',
            'provider' => $provider,
        ]);
    }

    public function getProvider(string $provider): User | Retailer | Exception
    {
        if($provider === 'user')
            return new User();
        else if($provider === 'provider')
            return new Retailer();
        else
            return throw new InvalidDataProviderException('Wrong provider provided');
    }
}
