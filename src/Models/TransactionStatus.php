<?php

namespace Err0r\Laratransaction\Models;

use Carbon\Carbon;
use Err0r\Laratransaction\Traits\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $slug
 * @property array $name
 * @property array|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Transaction> $transactions
 */
class TransactionStatus extends Model
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

        $this->setTable(config('laratransaction.tables.transaction_statuses.name'));
    }

    public function transactions()
    {
        return $this->hasMany(config('laratransaction.models.transaction'));
    }
}
