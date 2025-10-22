<?php

use Hypersender\Http\Controllers\SmsWebhookController;
use Hypersender\Http\Controllers\WhatsappWebhookController;
use Illuminate\Support\Facades\Route;

Route::post(config('hypersender-config.whatsapp_webhook_route'), WhatsappWebhookController::class)
    ->name('hypersender.whatsapp.webhook');

Route::post(config('hypersender-config.sms_webhook_route'), SmsWebhookController::class)
    ->name('hypersender.sms.webhook');
