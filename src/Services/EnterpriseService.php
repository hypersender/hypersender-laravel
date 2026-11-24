<?php

namespace Hypersender\Services;

use Hypersender\Clients\Enterprise\EnterpriseClient;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @mixin EnterpriseClient
 */
class EnterpriseService
{
    use ForwardsCalls;

    public function __construct(
        protected EnterpriseClient $enterpriseClient,
    ) {}

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->enterpriseClient, $method, $parameters);
    }
}
