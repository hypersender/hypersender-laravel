<?php

namespace Hypersender\Hypersender\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hypersender\Hypersender\Hypersender
 */
class Hypersender extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Hypersender\Hypersender\Hypersender::class;
    }
}
