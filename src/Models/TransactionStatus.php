<?php

namespace Err0r\Laratransaction\Models;

use Err0r\Laratransaction\Traits\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TransactionStatus extends Model
{
    use HasFactory;
    use HasTranslations;
    use Sluggable;
    use HasUuids;

    public $translatable = [
        'name',
        'description',
    ];

    protected $fillable = [
        'slug',
        'name',
        'description',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('laratransaction.tables.transaction_statuses.name'));
    }

    public function transactions()
    {
        return $this->hasMany(config('laratransaction.models.transaction'));
    }
}
