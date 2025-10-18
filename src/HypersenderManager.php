<?php

namespace Hypersender;

use Hypersender\Services\HypersenderSmsService;
use Hypersender\Services\HypersenderWhatsappService;

class HypersenderManager
{
    public function __construct(
        protected HypersenderWhatsappService $whatsappService,
        protected HypersenderSmsService $smsService,
    ) {}

    public function whatsapp(): HypersenderWhatsappService
    {
        return $this->whatsappService;
    }

    public function sms(): HypersenderSmsService
    {
        return $this->smsService;
    }
}
