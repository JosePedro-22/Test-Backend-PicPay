<?php

namespace App\Observers;

use App\Models\Retailer;

class RetailerObserver
{
    public function created(Retailer $retailer): void
    {
        $retailer->wallet()->create(['balance' => 0]);
    }
}
