<?php

namespace App\Repositories;

use App\Models\Retailer;
use App\Models\Transactoins\Transaction;
use App\Models\User;
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

        if(!$payee = $this->retrievePayee($request)){
            throw new InvalidDataProviderException('testando');
        }

        $myWallet = Auth::guard($request['provider'])->user()->wallet;

        if(!$this->checkUserBalance($myWallet, $request['amount'])){
            throw new \Exception('balance in the card is not enough', 422);
        }

        return $this->makeTransaction($payee, $request);
    }
    private function retrievePayee(Request $request)
    {
        try{
            $model = $this->getProvider($request['provider']);

            return $model->findOrFail($request['payee_id']);
        }catch(InvalidDataProviderException | Exception $e){
            return false;
        }
        
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

    private function checkUserBalance($user, mixed $amount)
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

            return $transaction;
        });
    }
}
