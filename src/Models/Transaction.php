<?php

namespace Err0r\Laratransaction\Models;

use Err0r\Laratransaction\Enums\TransactionStatus as TransactionStatusEnum;
use Err0r\Laratransaction\Enums\TransactionType as TransactionTypeEnum;
use Err0r\Laratransaction\Enums\PaymentMethod as PaymentMethodEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Translatable\HasTranslations;

class Transaction extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;

    public $translatable = ['currency'];

    protected $with = [
        'status',
        'type',

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

    public function isPending(): bool
    {
        return $this->status->slug === TransactionStatusEnum::PENDING->value;
    }

    public function isCompleted(): bool
    {
        return $this->status->slug === TransactionStatusEnum::COMPLETED->value;
    }

    public function isFailed(): bool
    {
        return $this->status->slug === TransactionStatusEnum::FAILED->value;
    }

    public function isCancelled(): bool
    {
        return $this->status->slug === TransactionStatusEnum::CANCELLED->value;
    }

    /**
     * @param TransactionStatusEnum|string $status
     */
    public function setStatus($status): Transaction
    {
        $slug = $status instanceof TransactionStatusEnum ? $status->value : $status;
        return $this->status()->associate(TransactionStatus::slug($slug)->first());
    }

    /**
     * @param TransactionType|string $type
     */
    public function setType($type): Transaction
    {
        $slug = $type instanceof TransactionTypeEnum ? $type->value : $type;
        return $this->type()->associate(TransactionType::slug($slug)->first());
    }

    /**
     * @param PaymentMethod|string $paymentMethod
     */
    public function setPaymentMethod($paymentMethod): Transaction
    {
        $slug = $paymentMethod instanceof PaymentMethodEnum ? $paymentMethod->value : $paymentMethod;
        return $this->paymentMethod()->associate(PaymentMethod::slug($slug)->first());
    }
}
