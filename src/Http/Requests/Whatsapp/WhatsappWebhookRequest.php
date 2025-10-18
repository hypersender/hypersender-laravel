<?php

namespace Hypersender\Http\Requests\Whatsapp;

use Hypersender\Enums\WhatsappWebhookEventEnum;
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
            $authorizationSecret = config('hypersender-laravel.whatsapp_webhook_authorization_secret');

            if ($authorizationSecret === null || $authorizationSecret === '') {
                return;
            }

            if ($this->header('authorization') !== $authorizationSecret) {
                abort(401, 'Unauthorized WhatsApp webhook request. Please check your authorization secret.');
            }
        });
    }

    public function payload(): array
    {
        return $this->all();
    }

    public function secret(): ?string
    {
        return $this->header('authorization');
    }
}
