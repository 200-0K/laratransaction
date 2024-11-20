<?php

namespace Err0r\Laratransaction\Models;

use Err0r\Laratransaction\Builders\TransactionBuilder;
use Err0r\Laratransaction\Enums\PaymentMethod as PaymentMethodEnum;
use Err0r\Laratransaction\Enums\TransactionStatus as TransactionStatusEnum;
use Err0r\Laratransaction\Enums\TransactionType as TransactionTypeEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory;
    use HasUuids;

    protected $with = [
        'status',
        'type',
        'paymentMethod',
    ];

    protected $fillable = [
        'status_id',
        'type_id',
        'payment_method_id',
        'amount',
        'currency',
        'gateway',
        'gateway_transaction_id',
        'metadata',
        'processed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('laratransaction.tables.transactions.name'));
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(config('laratransaction.models.transaction_status'));
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(config('laratransaction.models.transaction_type'));
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(config('laratransaction.models.payment_method'));
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePending($query): Builder
    {
        return $query->whereHas('status', fn ($query) => $query->slug(TransactionStatusEnum::PENDING->value));
    }

    public function scopeCompleted($query): Builder
    {
        return $query->whereHas('status', fn ($query) => $query->slug(TransactionStatusEnum::COMPLETED->value));
    }

    public function scopeFailed($query): Builder
    {
        return $query->whereHas('status', fn ($query) => $query->slug(TransactionStatusEnum::FAILED->value));
    }

    public function scopeCancelled($query): Builder
    {
        return $query->whereHas('status', fn ($query) => $query->slug(TransactionStatusEnum::CANCELLED->value));
    }

    /**
     * @param  string|TransactionStatus|TransactionStatusEnum  $status
     */
    public function isStatus($status): bool
    {
        $slug = match (true) {
            $status instanceof TransactionStatus => $status->slug,
            $status instanceof TransactionStatusEnum => $status->value,
            default => $status,
        };

        return $this->status->slug === $slug;
    }

    /**
     * @param  string|TransactionType|TransactionTypeEnum  $type
     */
    public function isType($type): bool
    {
        $slug = match (true) {
            $type instanceof TransactionType => $type->slug,
            $type instanceof TransactionTypeEnum => $type->value,
            default => $type,
        };

        return $this->type->slug === $slug;
    }

    /**
     * @param  TransactionStatus|TransactionStatusEnum|string  $status
     */
    public function setStatus($status): Transaction
    {
        $status = match (true) {
            $status instanceof TransactionStatus => $status,
            $status instanceof TransactionStatusEnum => TransactionStatus::slug($status->value)->first(),
            default => TransactionStatus::slug($status)->first(),
        };

        return $this->status()->associate($status);
    }

    /**
     * @param  TransactionType|TransactionTypeEnum|string  $type
     */
    public function setType($type): Transaction
    {
        $type = match (true) {
            $type instanceof TransactionType => $type,
            $type instanceof TransactionTypeEnum => TransactionType::slug($type->value)->first(),
            default => TransactionType::slug($type)->first(),
        };

        return $this->type()->associate($type);
    }

    /**
     * @param  PaymentMethod|PaymentMethodEnum|string  $paymentMethod
     */
    public function setPaymentMethod($paymentMethod): Transaction
    {
        $paymentMethod = match (true) {
            $paymentMethod instanceof PaymentMethod => $paymentMethod,
            $paymentMethod instanceof PaymentMethodEnum => PaymentMethod::slug($paymentMethod->value)->first(),
            default => PaymentMethod::slug($paymentMethod)->first(),
        };

        return $this->paymentMethod()->associate($paymentMethod);
    }

    public function markAsCompleted(): bool
    {
        $this->status_id = TransactionStatus::slug(TransactionStatusEnum::COMPLETED->value)->first()->id;
        $this->processed_at = now();
        
        return $this->save();
    }

    public function markAsFailed(): bool
    {
        $this->status_id = TransactionStatus::slug(TransactionStatusEnum::FAILED->value)->first()->id;
        
        return $this->save();
    }

    public function markAsCancelled(): bool
    {
        $this->status_id = TransactionStatus::slug(TransactionStatusEnum::CANCELLED->value)->first()->id;
        
        return $this->save();
    }

    public static function builder(): TransactionBuilder
    {
        return TransactionBuilder::create();
    }
}
