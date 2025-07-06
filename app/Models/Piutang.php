<?php

namespace App\Models;


use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\PiutangProduct;
use App\Models\PiutangAgreement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Piutang extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($piutang) {
            if (!$piutang->uuid) {
                $piutang->uuid = Str::uuid();
            }
        });
    }

    /**
     * Get the user associated with the Piutang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Relasi ke perjanjian (MOU) piutang
     */
    public function agreement(): HasOne
    {
        return $this->hasOne(PiutangAgreement::class);
    }

    /**
     * The products that belong to the Piutang
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'piutang_products')
            ->using(PiutangProduct::class)
            ->withPivot('price', 'qty');
    }

    /**
     * Get all of the paymentPiutangs for the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function paymentPiutangs(): HasMany
    {
        return $this->hasMany(PaymentPiutang::class, 'piutang_id');
    }

    /**
     * Get the transactions that owns the Piutang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
