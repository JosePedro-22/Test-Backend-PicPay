<?php

namespace App\Models\Transactoins;

use App\Models\Retailer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'retailer_id',
        'balance'
    ];

    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function retailer(): BelongsTo
    {
        return $this->belongsTo(Retailer::class);
    }

    public function deposit($value): void
    {
        $this->update([
            'balance' => $this->attributes['balance'] += $value
        ]);
    }

    public function withDraw($value): void
    {
        $this->update([
            'balance' => $this->attributes['balance'] -=  $value
        ]);
    }
}
