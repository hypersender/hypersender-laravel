<?php

namespace Hypersender\Hypersender\Facades;

use Hypersender\Hypersender\HypersenderClient;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Hypersender\Hypersender\HypersenderClient
 */
class Hypersender extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HypersenderClient::class;
    }
}
