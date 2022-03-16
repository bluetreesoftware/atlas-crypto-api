<?php

namespace App\Models\Exchange;

use App\Enums\Exchange\OrderActionEnum;
use App\Enums\Exchange\OrderStatusEnum;
use App\Enums\Exchange\OrderTypeEnum;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
