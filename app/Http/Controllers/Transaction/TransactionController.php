<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Repositories\TransactionRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\InvalidDataProviderException;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository
    )
    {}

    public function postTransaction(Request $request): JsonResponse|array
    {
        try {
            $this->validate($request, [
                'provider' => 'required | in:user,retailer',
                'payee_id' => 'required',
                'amount' => 'required | numeric'
            ]);
        }catch (ValidationException $exception){
            return response()->json([
                'errors' => [
                    'provider' =>[
                        $exception->getMessage()
                    ]
                ],
            ], 422);
        }

        try {
            $fields = $request->only(['provider', 'payee_id', 'amount']);
            $result = $this->transactionRepository->handle($fields);
            return response()->json($result);
        } catch (InvalidDataProviderException| Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], 422);
        } catch (InvalidDataProviderException $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], $exception->getCode());
        } catch (Exception $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], 401);
        }
    }

}
