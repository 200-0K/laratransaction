<?php

namespace Err0r\Laratransaction\Models;

use Err0r\Laratransaction\Traits\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TransactionType extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;
    use Sluggable;

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

        $this->setTable(config('laratransaction.tables.transaction_types.name'));
    }

    public function transactions()
    {
        return $this->hasMany(config('laratransaction.models.transaction'));
    }
}
