<?php

namespace App\Models;

use App\Models\Exchange\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read int $id
 * @property-read string $payment_id
 * @property-read string $email
 * @property-read string $first_name
 * @property-read string $last_name
 * @property-read Collection<Order> $orders
 */
class Account extends User
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'payment_id',
        'email',
        'first_name',
        'last_name',
        'password',
        'status'
    ];

    /**
     * @return HasMany
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
