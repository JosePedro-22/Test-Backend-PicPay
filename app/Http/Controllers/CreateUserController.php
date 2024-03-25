<?php

namespace App\Http\Controllers;

use App\Repositories\CreateUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CreateUserController extends Controller
{
    public function __construct(
        private readonly CreateUserRepository $createUserRepository
    ){}

    public function store(Request $request, string $provider): JsonResponse
    {
        try {
            return $this->createUserRepository->createUser($request, $provider);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
