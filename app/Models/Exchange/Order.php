<?php

namespace App\Models\Exchange;

use App\Enums\Exchange\OrderActionEnum;
use App\Enums\Exchange\OrderStatusEnum;
use App\Enums\Exchange\OrderTypeEnum;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read int price
 * @property-read int $quantity
 * @property-read int $actual_quantity
 * @method static self market()
 * @method static self limit()
 * @method static self buy()
 * @method static self sale()
 * @method static self open()
 * @method static self canceled()
 * @method static self completed()
 */
class Order extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account_id',
        'trade_id',
        'price',
        'type',
        'quantity',
        'actual_quantity',
        'status',
        'action'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'action' => OrderActionEnum::class,
        'status' => OrderStatusEnum::class,
        'type' => OrderTypeEnum::class
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
    public function trade()
    {
        return $this->belongsTo(Trade::class);
    }

    /**
     * @return BelongsToMany
     */
    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeMarket(Builder $query): Builder
    {
        return  $query->where('type', OrderTypeEnum::Market);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeLimit(Builder $query): Builder
    {
        return $query->where('type', OrderTypeEnum::Limit);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeBuy(Builder $query): Builder
    {
        return $query->where('action', OrderActionEnum::Buy);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeSale(Builder $query): Builder
    {
        return $query->where('action', OrderActionEnum::Sale);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', OrderStatusEnum::Open);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeCanceled(Builder $query): Builder
    {
        return $query->where('status', OrderStatusEnum::Completed);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', OrderStatusEnum::Completed);
    }
}
