<?php

namespace App\Models\Transactoins;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $payload)
 */
class Transaction extends Model
{
    use HasFactory;

    protected $table ='wallet_transactions';

    protected $fillable = [
        'payer_wallet_id',
        'payee_wallet_id',
        'amount'
    ];

    public function walletPayer(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'payer_wallet_id');
    }

    public function walletPayee(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'payee_wallet_id');
    }

}
