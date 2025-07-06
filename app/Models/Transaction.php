<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (!$transaction->uuid) {
                $transaction->uuid = Str::uuid();
            }
        });
    }

    /**
     * Get all of the paymentPiutangs for the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentPiutangs(): HasMany
    {
        return $this->hasMany(PaymentPiutang::class, 'transaction_id');
    }


    /**
     * Get the user that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function piutang(): BelongsTo
    {
        return $this->belongsTo(Piutang::class, 'piutang_id', 'id');
    }
}
