<?php

namespace Hypersender;

use Hypersender\Services\EnterpriseService;
use Hypersender\Services\HypersenderSmsService;
use Hypersender\Services\HypersenderWhatsappService;
use Hypersender\Services\OtpService;

class HypersenderManager
{
    public function __construct(
        protected HypersenderWhatsappService $whatsappService,
        protected HypersenderSmsService $smsService,
        protected OtpService $otpService,
        protected EnterpriseService $enterpriseService,
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

    public function enterprise(): EnterpriseService
    {
        return $this->enterpriseService;
    }
}
