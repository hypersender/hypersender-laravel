<?php

namespace Hypersender\Hypersender\Services;

use Hypersender\Hypersender\Clients\Sms\HypersenderSmsClient;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @mixin HypersenderSmsClient
 */
class HypersenderSmsService
{
    use ForwardsCalls;

    public function __construct(
        protected HypersenderSmsClient $smsClient,
    ) {}

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->smsClient, $method, $parameters);
    }
}
