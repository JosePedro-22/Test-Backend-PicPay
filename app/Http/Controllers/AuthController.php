<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthRepository $repository;
    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }
    public function postAuthenticate(Request $request, string $provider): JsonResponse
    {
        $result = $this->repository->authenticate($provider, $request);

        return response()->json([
            'token' => $result->plainTextToken,
            'expires_at' => $result->expires_at ?? '',
            'provider' => $provider,
        ]);
    }
}
