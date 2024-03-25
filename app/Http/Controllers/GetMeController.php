<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetMeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function me(): JsonResponse
    {
        return response()->json(Auth::user());
    }
}
