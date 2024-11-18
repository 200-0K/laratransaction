<?php

namespace Err0r\Laratransaction\Traits;

use Illuminate\Support\Facades\DB;

trait Sluggable
{
    public function scopeSlug($query, string $slug)
    {
        return $query->where(DB::raw('LOWER(slug)'), strtolower($slug));
    }
}
