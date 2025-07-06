<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (!$customer->uuid) {
                $customer->uuid = Str::uuid();
            }
        });
    }

    /**
     * Get the setting associated with the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function setting(): HasOne
    {
        return $this->hasOne(Setting::class, 'id', 'setting_id');
    }


    /**
     * Get the setting associated with the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function piutangs(): HasMany
    {
        return $this->hasMany(Piutang::class, 'user_id');
    }
}
