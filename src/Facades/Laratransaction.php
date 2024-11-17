<?php

namespace Err0r\Laratransaction\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Err0r\Laratransaction\Laratransaction
 */
class Laratransaction extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Err0r\Laratransaction\Laratransaction::class;
    }
}
