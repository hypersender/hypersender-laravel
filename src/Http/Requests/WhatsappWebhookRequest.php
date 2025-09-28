<?php

namespace Hypersender\Hypersender\Http\Requests;

use Hypersender\Hypersender\Enums\WhatsappWebhookEventEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class WhatsappWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event' => ['required', 'string', new Enum(WhatsappWebhookEventEnum::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $authorization = config('hypersender-laravel.whatsapp_webhook_authorization');
            $authorizationSecret = config('hypersender-laravel.whatsapp_webhook_authorization_secret');

            if ($this->header($authorization) !== $authorizationSecret) {
                $validator->errors()->add('authorization', 'Invalid authorization header. Please set HYPERSENDER_WHATSAPP_WEBHOOK_AUTHORIZATION_SECRET in your environment.');
            }
        });
    }

    public function payload(): array
    {
        return $this->all();
    }

    public function secret(): ?string
    {
        return $this->header(
            config('hypersender-laravel.whatsapp_webhook_authorization')
        );
    }
}
