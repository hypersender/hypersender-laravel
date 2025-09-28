<?php

namespace Hypersender\Hypersender\Http\Controllers;

use Hypersender\Hypersender\Contracts\WhatsappWebhookJobInterface;
use Hypersender\Hypersender\Http\Requests\WhatsappWebhookRequest;
use Illuminate\Routing\Controller;

class WhatsappWebhookController extends Controller
{
    public function __invoke(WhatsappWebhookRequest $request)
    {
        $jobClass = config('hypersender-laravel.whatsapp_webhook_job');

        if (! is_string($jobClass) || ! class_exists($jobClass)) {
            abort(500, 'Invalid WhatsApp webhook job class.');
        }

        if (! is_subclass_of($jobClass, WhatsappWebhookJobInterface::class)) {
            abort(500, 'WhatsApp webhook job must implement the required interface.');
        }

        $jobClass::dispatch(
            payload: $request->payload(),
            secret: $request->secret(),
        );

        return response()->json(['status' => 'ok']);
    }
}
