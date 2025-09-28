<?php

namespace Hypersender\Hypersender\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
            'event' => ['required', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $signatureHeader = config('hypersender-laravel.whatsapp_webhook_signature_header');

            if (! $this->headers->has($signatureHeader)) {
                $validator->errors()->add($signatureHeader, "Missing signature header '{$signatureHeader}'.");
            }
        });
    }

    public function payload(): array
    {
        return $this->all();
    }

    public function signature(): ?string
    {
        $signatureHeader = config('hypersender-laravel.whatsapp_webhook_signature_header');

        return $this->header($signatureHeader);
    }
}
