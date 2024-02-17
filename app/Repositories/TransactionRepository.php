<?php

namespace App\Repositories;

use App\Models\Retailer;
use App\Models\User;
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
//        if(!$this->getGuard())
//            return throw new \Exception('Retailer is not authorized to make transactions', 401);

        $model = $this->getProvider($request['provider']);
//        dd('oi');

        $user = $model->findOrFail($request['payee_id']);

//        $user->wallet()->transaction()->create([
//
//        ]);

        return [];
    }
    public function getGuard(): string
    {
        if(Auth::guard('users')->check())
            return true;
        else if(Auth::guard('retailer')->check())
            return false;
        else
            return throw new InvalidDataProviderException('Wrong Auth Guard', 422);
    }

    public function getProvider(string $provider): User | Retailer | Exception
    {
        if($provider === 'user')
            return new User();
        else if($provider === 'provider')
            return new Retailer();
        else
            return throw new InvalidDataProviderException('Wrong provider provided', 422);
    }
}
