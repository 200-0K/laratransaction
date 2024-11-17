<?php

namespace Err0r\Laratransaction\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTransaction
{
    public function transactions(): MorphMany
    {
        return $this->morphMany(config('laratransaction.models.transaction'), 'transactionable');
    }

    public function latestTransaction()
    {
        return $this->morphOne(config('laratransaction.models.transaction'), 'transactionable')->latest();
    }
}
