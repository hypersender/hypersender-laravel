<?php

use Hypersender\Hypersender\Jobs\ProcessWhatsappWebhookJob;

return [
    'base_url' => env('HYPERSENDER_BASE_URL', 'https://app.hypersender.com/api/whatsapp/v1'),
    'api_key' => env('HYPERSENDER_API_KEY'),
    'instance_id' => env('HYPERSENDER_INSTANCE_ID'),

    /* WhatsApp */
    'whatsapp_webhook_authorization_secret' => env('HYPERSENDER_WHATSAPP_WEBHOOK_AUTHORIZATION_SECRET'),
    'whatsapp_webhook_route' => env('HYPERSENDER_WHATSAPP_WEBHOOK_ROUTE', 'whatsapp/webhook'),
    'whatsapp_webhook_job' => env('HYPERSENDER_WHATSAPP_WEBHOOK_JOB', ProcessWhatsappWebhookJob::class),
    'whatsapp_queue' => env('HYPERSENDER_WHATSAPP_QUEUE', 'default'),

    /* SMS */
    'sms_webhook_authorization' => env('HYPERSENDER_SMS_WEBHOOK_AUTHORIZATION', 'x-sms-webhook'),
    'sms_webhook_route' => env('HYPERSENDER_SMS_WEBHOOK_ROUTE', 'sms/webhook'),
    'sms_webhook_job' => env('HYPERSENDER_SMS_WEBHOOK_JOB', 'hypersender:sms-webhook'),
    'sms_queue' => env('HYPERSENDER_SMS_QUEUE', 'default'),
];
