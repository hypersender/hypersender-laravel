<?php

namespace Hypersender\Hypersender;

use Hypersender\Hypersender\Clients\Sms\HypersenderSmsClient;
use Hypersender\Hypersender\Clients\Whatsapp\HypersenderWhatsappClient;

class HypersenderManager
{
    public function __construct(
        protected HypersenderWhatsappClient $whatsappClient,
        protected HypersenderSmsClient $smsClient,
    ) {}

    public function whatsapp(): HypersenderWhatsappClient
    {
        return $this->whatsappClient;
    }

    public function sms(): HypersenderSmsClient
    {
        return $this->smsClient;
    }
}
