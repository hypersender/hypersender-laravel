<?php

namespace Hypersender\Services;

use Hypersender\Clients\Otp\OtpClient;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @mixin OtpClient
 */
class OtpService
{
    use ForwardsCalls;

    public function __construct(
        protected OtpClient $otpClient,
    ) {}

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->otpClient, $method, $parameters);
    }
}
