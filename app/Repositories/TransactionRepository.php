<?php

namespace App\Repositories;

use App\Events\SendNotification;
use App\Models\Retailer;
use App\Models\Transactoins\Transaction;
use App\Models\User;
use App\Services\MockyService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\InvalidDataProviderException;
use PHPUnit\Logging\Exception;

class TransactionRepository
{
    public function handle($request): Transaction
    {

        if(!$this->getGuard())
            return throw new InvalidDataProviderException('Retailer is not authorized to make transactions', 401);

        if(!$payee = $this->retrievePayee($request))
            throw new InvalidDataProviderException("Register {$request['payee_id']} not found");

        $myWallet = Auth::guard($request['provider'])->user()->wallet;

        if(!$this->checkUserBalance($myWallet, $request['amount']))
            throw new \Exception('balance in the card is not enough', 422);

        if (!$this->isServiceAbleToMakeTransaction())
            throw new Exception('Service is not responding. Try again later.');

        return $this->makeTransaction($payee, $request);
    }
    public function getGuard(): bool | InvalidDataProviderException
    {
        if(Auth::guard('users')->check()) {
            return true;
        }
        else if(Auth::guard('retailers')->check()) {
            return false;
        }
        else {
            return throw new InvalidDataProviderException('Wrong Auth Guard', 422);
        }
    }
    public function getProvider(string $provider): User | Retailer | InvalidDataProviderException
    {

        if($provider === 'user') {
            return new User();
        }
        else if($provider === 'retailer') {
            return new Retailer();
        }
        else {
            return throw new InvalidDataProviderException('Wrong provider provided', 422);
        }
    }
    private function retrievePayee(array $request)
    {
        $model = $this->getProvider($request['provider']);

        $registro = $model->find($request['payee_id']);

        if(empty($registro)){
            throw new ModelNotFoundException("Registro {$request['payee_id']} não encontrado",404);
        }
        return $registro;
    }
    private function checkUserBalance($user, mixed $amount): bool
    {
        return $user->wallet->balance >= $amount;
    }
    private function makeTransaction($payee ,$data){
        $payload = [
            'payer_wallet_id' => Auth::guard($data['provider'])->user()->wallet->id,
            'payee_wallet_id' => $payee->wallet->id,
            'amount' => $data['amount'],
        ];

        return DB::transaction(function() use($payload){
            $transaction = Transaction::create($payload);

            $transaction->walletPayer->withDraw($payload['amount']);
            $transaction->walletPayer->deposit($payload['amount']);


            event(new SendNotification($transaction));
            return $transaction;
        });
    }
    private function isServiceAbleToMakeTransaction(): bool
    {
        $service = app(MockyService::class)->authorizeTransaction();
        return $service['message'] == 'Autorizado';
    }
}
