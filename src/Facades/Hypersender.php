<?php

namespace Hypersender\Hypersender\Facades;

use Hypersender\Hypersender\HypersenderManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Hypersender\Hypersender\Services\HypersenderWhatsappService whatsapp()
 * @method static \Hypersender\Hypersender\Services\HypersenderSmsService sms()
 *
 * @see \Hypersender\Hypersender\HypersenderManager
 */
class Hypersender extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HypersenderManager::class;
    }
}
