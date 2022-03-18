<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read int $id
 * @property-read string $email
 * @property-read string $first_name
 * @property-read string $last_name
 */
class Account extends User
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var string[]
     */
    protected $fillable = [
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
}
