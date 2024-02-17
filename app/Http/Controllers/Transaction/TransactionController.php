<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Repositories\TransactionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\InvalidDataProviderException;

class TransactionController extends Controller
{
    private TransactionRepository $transactionRepository;
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function postTransaction(Request $request): JsonResponse|array
    {
        try {

            $this->validate($request, [
                'provider' => 'required | in:user,retailer',
                'payee_id' => 'required',
                'amount' => 'required | numeric'
            ]);

            return $this->transactionRepository->handle($request);
        }catch (ValidationException $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]],$e->status);
        }
    }

}
