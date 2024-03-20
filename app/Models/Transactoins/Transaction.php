<?php

namespace App\Models\Transactoins;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $table ='wallet_transactions';

    protected $fillable = [
        'player_wallet_id',
        'playee_wallet_id',
        'amount'
    ];

    public function walletPayer(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'player_wallet_id');
    }

    public function walletPayee(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'playee_wallet_id');
    }

}
