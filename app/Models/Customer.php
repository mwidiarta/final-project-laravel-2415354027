<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'address',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    /**
     * Satu customer bisa memiliki banyak subscription (1 to Many)
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
