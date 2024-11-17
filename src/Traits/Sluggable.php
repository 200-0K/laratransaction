<?php

namespace Err0r\Laratransaction\Traits;

trait Sluggable
{
    public function scopeSlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }
}
