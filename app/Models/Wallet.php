<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * @property-read int $id
 * @property-read string $external_id
 * @property-read int $account_id
 * @property-read Currency $currency
 * @property-read Collection<Transaction> $transactions
 */
class Wallet extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account_id',
        'external_id',
        'currency_id'
    ];

    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'sender_wallet_id')->orWhere('recipient_wallet_id', $this->id);
    }

    /**
     * @return HasMany
     */
    public function sentTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'sender_wallet_id');
    }

    /**
     * @return HasMany
     */
    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'recipient_wallet_id');
    }

    /**
     * @param Builder $query
     * @param Currency $currency
     * @return Builder
     */
    public function scopeForCurrency($query, Currency $currency)
    {
        return $query->where('currency_id', $currency->id);
    }
}
