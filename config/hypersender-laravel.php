<?php

use Hypersender\Hypersender\Jobs\ProcessWhatsappWebhookJob;

return [
    'base_url' => env('HYPERSENDER_BASE_URL', 'https://app.hypersender.com/api/whatsapp/v1'),
    'api_key' => env('HYPERSENDER_API_KEY'),
    'instance_id' => env('HYPERSENDER_INSTANCE_ID'),

    /* WhatsApp */
    'whatsapp_webhook_signature_header' => env('HYPERSENDER_WHATSAPP_WEBHOOK_SIGNATURE_HEADER', 'x-whatsapp-webhook'),
    'whatsapp_webhook_route' => env('HYPERSENDER_WHATSAPP_WEBHOOK_ROUTE', 'whatsapp/webhook'),
    'whatsapp_webhook_job' => env('HYPERSENDER_WHATSAPP_WEBHOOK_JOB', ProcessWhatsappWebhookJob::class),

    /* SMS */
    'sms_webhook_signature_header' => env('HYPERSENDER_SMS_WEBHOOK_SIGNATURE_HEADER', 'x-sms-webhook'),
    'sms_webhook_route' => env('HYPERSENDER_SMS_WEBHOOK_ROUTE', 'sms/webhook'),
    'sms_webhook_job' => env('HYPERSENDER_SMS_WEBHOOK_JOB', 'hypersender:sms-webhook'),
];
