<?php

namespace Hypersender\Facades;

use Hypersender\HypersenderManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Hypersender\Services\HypersenderWhatsappService whatsapp()
 * @method static \Hypersender\Services\HypersenderSmsService sms()
 *
 * @see \Hypersender\HypersenderManager
 */
class Hypersender extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HypersenderManager::class;
    }
}
