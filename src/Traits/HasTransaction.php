<?php

namespace Err0r\Laratransaction\Traits;

use Err0r\Laratransaction\Builders\TransactionBuilder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property \Illuminate\Database\Eloquent\Collection<int, \Err0r\Laratransaction\Models\Transaction> $transactions
 * @property \Err0r\Laratransaction\Models\Transaction|null $latestTransaction
 * @property \Err0r\Laratransaction\Builders\TransactionBuilder $transactionBuilder
 */
trait HasTransaction
{
    public function transactions(): MorphMany
    {
        return $this->morphMany(config('laratransaction.models.transaction'), 'transactionable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function latestTransaction(): MorphOne
    {
        return $this->morphOne(config('laratransaction.models.transaction'), 'transactionable')->latest();
    }

    public function transactionBuilder(): TransactionBuilder
    {
        return TransactionBuilder::create($this);
    }
}
