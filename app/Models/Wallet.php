<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'value',
    ];

    public function User(): BelongsTo
    {
        $this->BelongsTo(User::class);
    }
}
