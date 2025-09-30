<?php

use Hypersender\Hypersender\Http\Controllers\WhatsappWebhookController;
use Illuminate\Support\Facades\Route;

Route::post(config('hypersender-laravel.whatsapp_webhook_route'), WhatsappWebhookController::class)
    ->name('hypersender.whatsapp.webhook');
