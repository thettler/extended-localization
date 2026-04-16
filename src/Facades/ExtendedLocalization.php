<?php

namespace Thettler\ExtendedLocalization\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thettler\ExtendedLocalization\ExtendedLocalization
 */
class ExtendedLocalization extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Thettler\ExtendedLocalization\ExtendedLocalization::class;
    }
}
