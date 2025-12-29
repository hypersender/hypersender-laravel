<?php

namespace Hypersender;

use Hypersender\Services\HypersenderSmsService;
use Hypersender\Services\HypersenderWhatsappService;
use Hypersender\Services\OtpService;

class HypersenderManager
{
    public function __construct(
        protected HypersenderWhatsappService $whatsappService,
        protected HypersenderSmsService $smsService,
        protected OtpService $otpService,
    ) {}

    public function whatsapp(): HypersenderWhatsappService
    {
        return $this->whatsappService;
    }

    public function sms(): HypersenderSmsService
    {
        return $this->smsService;
    }

    public function otp(): OtpService
    {
        return $this->otpService;
    }
}
