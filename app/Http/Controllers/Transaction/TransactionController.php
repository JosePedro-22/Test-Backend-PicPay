<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Repositories\TransactionRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $this->validate($request, [
            'provider' => 'required | in:user,retailer',
            'payee_id' => 'required',
            'amount' => 'required | numeric'
        ]);

        try {
            $fields = $request->only(['provider', 'payee_id', 'amount']);
            $result = $this->transactionRepository->handle($fields);
                return response()->json($result);
        }catch (InvalidDataProviderException|Exception $e) {
            return response()->json(['errors' => ['main' => $e->getMessage()]], $e->getCode());
        }
        catch (InvalidDataProviderException | Exception $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], $exception->getCode());
        } catch (Exception | Exception $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], 401);
        } catch (\Exception $exception) {
            Log::critical('[Transaction Gone So Fucking Wrong plz call the cops]', [
                'message' => $exception->getMessage()
            ]);
        }
    }

}
