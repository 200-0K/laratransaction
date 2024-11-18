<?php

namespace Err0r\Laratransaction\Traits;

use Err0r\Laratransaction\Builders\TransactionBuilder;
use Err0r\Laratransaction\Models\Transaction;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTransaction
{
    public function transactions(): MorphMany
    {
        return $this->morphMany(config('laratransaction.models.transaction'), 'transactionable');
    }

    /**
     * @return Transaction
     */
    public function latestTransaction()
    {
        return $this->morphOne(config('laratransaction.models.transaction'), 'transactionable')->latest();
    }

    public function transactionBuilder(): TransactionBuilder
    {
        return TransactionBuilder::create($this);
    }
}
