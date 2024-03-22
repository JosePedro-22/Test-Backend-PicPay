<?php

namespace App\Events;


use App\Models\Transactions\Transaction;
class SendNotification
{
    public function __construct(
        public Transaction $transaction
    )
    {}
}
