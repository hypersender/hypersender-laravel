<?php

namespace Hypersender\Hypersender\Services;

use Hypersender\Hypersender\Clients\Whatsapp\HypersenderWhatsappClient;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @mixin HypersenderWhatsappClient
 */
class HypersenderWhatsappService
{
    use ForwardsCalls;

    public function __construct(
        protected HypersenderWhatsappClient $whatsappClient,
    ) {}

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->whatsappClient, $method, $parameters);
    }
}
