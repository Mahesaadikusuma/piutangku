<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentPiutang extends Model
{



    /**
     * Get the piutang that owns the PaymentPiutang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function piutang(): BelongsTo
    {
        return $this->belongsTo(Piutang::class, 'piutang_id', 'id');
    }


    /**
     * Get the transaction that owns the PaymentPiutang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
