<?php

namespace App\Models;

use App\Enums\Transaction\TransactionStatusEnum;
use App\Enums\Transaction\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read int $volume
 * @property-read int $status
 * @property-read TransactionTypeEnum $type
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'sender_wallet_id',
        'recipient_wallet_id',
        'volume',
        'status',
        'type'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status' => TransactionStatusEnum::class,
        'type' => TransactionTypeEnum::class
    ];

    /**
     * @return BelongsTo
     */
    public function senderWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'sender_wallet_id');
    }

    /**
     * @return BelongsTo
     */
    public function recipientWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'recipient_wallet_id');
    }

    public function scopeAuthorised($query)
    {
        $query->where('status', TransactionStatusEnum::Authorised);
    }
}
