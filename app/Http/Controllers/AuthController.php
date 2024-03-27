<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\InvalidDataProviderException;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthRepository $repository
    ){}
    public function postAuthenticate(Request $request, string $provider): JsonResponse
    {
        try {
            return $this->repository->authenticate($provider, $request);
        }catch (InvalidDataProviderException $exception){
            return response()->json(['errors' => ['main' => $exception->getMessage()]], 422);
        }catch (AuthenticationException $exception){
            return response()->json(['errors' => ['main' => $exception->getMessage()]], 401);
        }
    }
}
