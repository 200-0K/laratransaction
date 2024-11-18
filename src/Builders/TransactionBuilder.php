<?php

namespace Err0r\Laratransaction\Builders;

use Carbon\Carbon;
use Err0r\Laratransaction\Enums\PaymentMethod as PaymentMethodEnum;
use Err0r\Laratransaction\Enums\TransactionStatus as TransactionStatusEnum;
use Err0r\Laratransaction\Enums\TransactionType as TransactionTypeEnum;
use Err0r\Laratransaction\Models\PaymentMethod;
use Err0r\Laratransaction\Models\Transaction;
use Err0r\Laratransaction\Models\TransactionStatus;
use Err0r\Laratransaction\Models\TransactionType;
use Illuminate\Database\Eloquent\Model;

class TransactionBuilder
{
    protected $attributes = [];

    protected $transactionable;

    public static function create(?Model $transactionable = null): self
    {
        $builder = new self;

        if ($transactionable) {
            $builder->transactionable($transactionable);
        }

        return $builder;
    }

    /**
     * @param  string|TransactionStatus|TransactionStatusEnum  $status
     */
    public function status($status): self
    {
        $this->attributes['status_id'] = match ($status) {
            $status instanceof TransactionStatus => $status->id,
            $status instanceof TransactionStatusEnum => TransactionStatus::slug($status->value)->first()->id,
            default => TransactionStatus::slug($status)->first()->id,
        };

        return $this;
    }

    /**
     * @param  string|TransactionType|TransactionTypeEnum  $type
     */
    public function type($type): self
    {
        $this->attributes['type_id'] = match ($type) {
            $type instanceof TransactionType => $type->id,
            $type instanceof TransactionTypeEnum => TransactionType::slug($type->value)->first()->id,
            default => TransactionType::slug($type)->first()->id,
        };

        return $this;
    }

    /**
     * @param  string|PaymentMethod|PaymentMethodEnum  $paymentMethod
     */
    public function paymentMethod($paymentMethod): self
    {
        $this->attributes['payment_method_id'] = match ($paymentMethod) {
            $paymentMethod instanceof PaymentMethod => $paymentMethod->id,
            $paymentMethod instanceof PaymentMethodEnum => PaymentMethod::slug($paymentMethod->value)->first()->id,
            default => PaymentMethod::slug($paymentMethod)->first()->id,
        };

        return $this;
    }

    public function amount(float $amount, ?string $currency = null): self
    {
        $this->attributes['amount'] = $amount;
        $this->attributes['currency'] = $currency ?? $this->attributes['currency'];

        return $this;
    }

    public function currency(string $currency): self
    {
        $this->attributes['currency'] = $currency;

        return $this;
    }

    public function gateway(string $gateway, ?string $gatewayTransactionId = null): self
    {
        $this->attributes['gateway'] = $gateway;
        $this->attributes['gateway_transaction_id'] = $gatewayTransactionId ?? $this->attributes['gateway_transaction_id'];

        return $this;
    }

    public function gatewayTransactionId(string $gatewayTransactionId): self
    {
        $this->attributes['gateway_transaction_id'] = $gatewayTransactionId;

        return $this;
    }

    public function metadata($metadata): self
    {
        $this->attributes['metadata'] = $metadata;

        return $this;
    }

    public function processedAt(Carbon $processedAt): self
    {
        $this->attributes['processed_at'] = $processedAt;

        return $this;
    }

    public function transactionable(Model $transactionable): self
    {
        $this->transactionable = $transactionable;

        return $this;
    }

    public function build(): Transaction
    {
        $transaction = new Transaction($this->attributes);
        if ($this->transactionable) {
            $transaction->transactionable()->associate($this->transactionable);
        }

        return $transaction;
    }

    public function save(): Transaction
    {
        $transaction = $this->build();
        $transaction->save();

        return $transaction;
    }
}
