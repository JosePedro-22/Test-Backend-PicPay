<?php

namespace App\Http\Resources;

use App\Models\Shopkeeper;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;

class TransfersResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $payer = User::findOrFail($request['payer']);
        $payee = User::findOrFail($request['payee']);

        $wallet_payer = Wallet::findOrFail($payer['wallet_id']);
        $wallet_payee = Wallet::findOrFail($payee['wallet_id']);

        $response = Http::get('https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc');
        $notification_transaction = Http::get('https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6');

        if(!$payer['shopkeeper'])
            if($payer['id'] !== $payee['id'] && $wallet_payer['value'] > $request['value'] && $response->successful()) {

                $wallet_payer['value'] -= $request['value'];
                $wallet_payee['value'] += $request['value'];

                $transaction = new Transfer();
                $transaction->value = $request['value'];
                $transaction->payer = $payer->id;
                $transaction->payee = $payee->id;

                $wallet_payer->save();
                $wallet_payee->save();
                $transaction->save();

                return [
                    'transaction' => "transaction successful",
                    'transaction_notification_trigger' => $notification_transaction->json(),
                    'status' => 201,
                    'info_transaction' => [
                        'transaction_value' => $request['value'],
                        'wallet_payer' => $wallet_payer['value'],
                        'wallet_payee' => $wallet_payee['value']
                    ],
                ];
            }

        return [
            'transaction' => "it was not possible to carry out the transaction"
        ];

    }
}
