<?php

use Hypersender\Jobs\ProcessSmsWebhookJob;
use Hypersender\Jobs\ProcessWhatsappWebhookJob;

return [
    /* WhatsApp */
    'whatsapp_api_key' => env('HYPERSENDER_WHATSAPP_API_KEY'),
    'whatsapp_instance_id' => env('HYPERSENDER_WHATSAPP_INSTANCE_ID'),
    'whatsapp_webhook_authorization_secret' => env('HYPERSENDER_WHATSAPP_WEBHOOK_AUTHORIZATION_SECRET'),
    'whatsapp_webhook_route' => env('HYPERSENDER_WHATSAPP_WEBHOOK_ROUTE', 'whatsapp/webhook'),
    'whatsapp_webhook_job' => env('HYPERSENDER_WHATSAPP_WEBHOOK_JOB', ProcessWhatsappWebhookJob::class),
    'whatsapp_queue' => env('HYPERSENDER_WHATSAPP_QUEUE', 'default'),

    /* SMS */
    'sms_api_key' => env('HYPERSENDER_SMS_API_KEY'),
    'sms_instance_id' => env('HYPERSENDER_SMS_INSTANCE_ID'),
    'sms_webhook_authorization_secret' => env('HYPERSENDER_SMS_WEBHOOK_AUTHORIZATION_SECRET', 'x-sms-webhook'),
    'sms_webhook_route' => env('HYPERSENDER_SMS_WEBHOOK_ROUTE', 'sms/webhook'),
    'sms_webhook_job' => env('HYPERSENDER_SMS_WEBHOOK_JOB', ProcessSmsWebhookJob::class),
    'sms_queue' => env('HYPERSENDER_SMS_QUEUE', 'default'),
];
