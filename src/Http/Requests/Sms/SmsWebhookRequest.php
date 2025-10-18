<?php

namespace Hypersender\Http\Requests\Sms;

use Hypersender\Enums\SmsWebhookEventEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class SmsWebhookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event' => ['required', 'string', new Enum(SmsWebhookEventEnum::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $authorizationSecret = config('hypersender-laravel.sms_webhook_authorization_secret');

            if ($authorizationSecret === null || $authorizationSecret === '') {
                return;
            }

            if ($this->header('authorization') !== $authorizationSecret) {
                abort(401, 'Unauthorized SMS webhook request. Please check your authorization secret.');
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
