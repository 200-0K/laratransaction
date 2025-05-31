<?php

namespace Err0r\Laratransaction\Models;

use Carbon\Carbon;
use Err0r\Laratransaction\Builders\TransactionBuilder;
use Err0r\Laratransaction\Enums\PaymentMethod as PaymentMethodEnum;
use Err0r\Laratransaction\Enums\TransactionStatus as TransactionStatusEnum;
use Err0r\Laratransaction\Enums\TransactionType as TransactionTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property string $transactionable_type
 * @property string $transactionable_id
 * @property string $status_id
 * @property string $type_id
 * @property string $payment_method_id
 * @property float $amount
 * @property string $currency
 * @property string|null $gateway
 * @property string|null $gateway_transaction_id
 * @property array|null $metadata
 * @property Carbon|null $processed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read TransactionStatus $status
 * @property-read TransactionType $type
 * @property-read PaymentMethod $paymentMethod
 * @property-read Model $transactionable
 */
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
        'amount' => 'float',
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

    public function scopePending(Builder $query): Builder
    {
        return $query->whereHas('status', fn ($query) => $query->slug(TransactionStatusEnum::PENDING->value));
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereHas('status', fn ($query) => $query->slug(TransactionStatusEnum::COMPLETED->value));
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->whereHas('status', fn ($query) => $query->slug(TransactionStatusEnum::FAILED->value));
    }

    public function scopeCancelled(Builder $query): Builder
    {
        return $query->whereHas('status', fn ($query) => $query->slug(TransactionStatusEnum::CANCELLED->value));
    }

    public function scopeWhereGateway(Builder $query, string $gateway): Builder
    {
        return $query->where('gateway', '=', $gateway);
    }

    public function scopeWhereGatewayTransactionId(Builder $query, string $gatewayTransactionId): Builder
    {
        return $query->where('gateway_transaction_id', '=', $gatewayTransactionId);
    }

    /**
     * @param  string|TransactionStatus|TransactionStatusEnum  $status
     */
    public function scopeWhereStatus(Builder $query, $status): Builder
    {
        return match (true) {
            $status instanceof TransactionStatus => $query->where('status_id', '=', $status->id),
            $status instanceof TransactionStatusEnum => $query->whereHas('status', fn ($query) => $query->slug($status->value)),
            default => $query->whereHas('status', fn ($query) => $query->slug($status)),
        };
    }

    /**
     * @param  string|TransactionType|TransactionTypeEnum  $type
     */
    public function scopeWhereType(Builder $query, $type): Builder
    {
        return match (true) {
            $type instanceof TransactionType => $query->where('type_id', '=', $type->id),
            $type instanceof TransactionTypeEnum => $query->whereHas('type', fn ($query) => $query->slug($type->value)),
            default => $query->whereHas('type', fn ($query) => $query->slug($type)),
        };
    }

    /**
     * @param  PaymentMethod|PaymentMethodEnum|string  $paymentMethod
     */
    public function scopeWherePaymentMethod(Builder $query, $paymentMethod): Builder
    {
        return match (true) {
            $paymentMethod instanceof PaymentMethod => $query->where('payment_method_id', '=', $paymentMethod->id),
            $paymentMethod instanceof PaymentMethodEnum => $query->whereHas('paymentMethod', fn ($query) => $query->slug($paymentMethod->value)),
            default => $query->whereHas('paymentMethod', fn ($query) => $query->slug($paymentMethod)),
        };
    }

    public function scopeWhereMetadata(Builder $query, string $key, $value): Builder
    {
        return $query->whereJsonContains('metadata', [$key => $value]);
    }

    /**
     * @param string|TransactionStatus|TransactionStatusEnum  $status
     */
    public function scopeUpdateStatus(Builder $query, $status): int
    {
        return $query->update(['status_id' => match (true) {
            $status instanceof TransactionStatus => $status->id,
            $status instanceof TransactionStatusEnum => TransactionStatus::slug($status->value)->first()->id,
            default => TransactionStatus::slug($status)->first()->id,
        }]);
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

    public function setAmount(float $amount, ?string $currency = null): Transaction
    {
        $this->amount = $amount;

        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }

        if ($currency !== null) {
            $this->currency = $currency;
        }

        $this->save();
        return $this;
    }

    public function setCurrency(string $currency): Transaction
    {
        $this->currency = $currency;

        $this->save();
        return $this;
    }

    public function setGateway(string $gateway, ?string $gatewayTransactionId = null): Transaction
    {
        $this->gateway = $gateway;
        if ($gatewayTransactionId !== null) {
            $this->gateway_transaction_id = $gatewayTransactionId;
        }

        $this->save();
        return $this;
    }

    public function setGatewayTransactionId(string $gatewayTransactionId): Transaction
    {
        $this->gateway_transaction_id = $gatewayTransactionId;

        $this->save();
        return $this;
    }

    public function setMetadata(array $metadata): Transaction
    {
        $this->metadata = $metadata;

        $this->save();
        return $this;
    }

    public function markAsCompleted(?Carbon $processedAt = null): bool
    {
        $this->status_id = TransactionStatus::slug(TransactionStatusEnum::COMPLETED->value)->first()->id;
        $this->processed_at = $processedAt ?? now();
        
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
