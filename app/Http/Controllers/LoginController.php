<?php

namespace App\Http\Controllers;

use App\Repositories\LoginRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class LoginController extends Controller
{
    public function __construct(
        private readonly LoginRepository $loginRepository
    ){}

    public function store(Request $request, string $provider): JsonResponse
    {
        try {
            return $this->loginRepository->login($request, $provider);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
