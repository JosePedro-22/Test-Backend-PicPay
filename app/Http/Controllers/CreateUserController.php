<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\CreateUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CreateUserController extends Controller
{
    private CreateUserRepository $createUserRepository;
    public function __construct(CreateUserRepository $createUserRepository)
    {
        $this->createUserRepository = $createUserRepository;
    }

    public function store(Request $request): JsonResponse
    {
        try {
            return $this->createUserRepository->createUser($request);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
