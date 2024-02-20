<?php

namespace App\Repositories;

use App\Models\Retailer;
use App\Models\User;
use GuzzleHttp\Exception\InvalidArgumentException;
use http\Exception\BadMethodCallException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\InvalidDataProviderException;
use PHPUnit\Logging\Exception;

class TransactionRepository
{
    /**
     * @throws \Exception
     */
    public function handle(Request $request): array
    {
        if(!$this->getGuard())
            return throw new InvalidDataProviderException('Retailer is not authorized to make transactions', 401);

        $model = $this->getProvider($request['provider']);

        $user = $model->findOrFail($request['payee_id']);

        if(!$this->checkUserBalance($user, $request['amount'])){
            throw new \Exception('balance in the card is not enough', 422);
        }

        return [];
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
}
