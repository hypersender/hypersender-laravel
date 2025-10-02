<?php

namespace Hypersender\Hypersender\Http\Controllers;

use Hypersender\Hypersender\Contracts\SmsWebhookJobInterface;
use Hypersender\Hypersender\Http\Requests\Sms\SmsWebhookRequest;
use Illuminate\Routing\Controller;

class SmsWebhookController extends Controller
{
    public function __invoke(SmsWebhookRequest $request)
    {
        $jobClass = config('hypersender-laravel.sms_webhook_job');

        if (! is_string($jobClass) || ! class_exists($jobClass)) {
            abort(500, 'Invalid SMS webhook job class.');
        }

        if (! is_subclass_of($jobClass, SmsWebhookJobInterface::class)) {
            abort(500, 'SMS webhook job must implement the required interface.');
        }

        $jobClass::dispatch(
            payload: $request->payload(),
            secret: $request->secret(),
        );

        return response()->json(['status' => 'ok']);
    }
}
