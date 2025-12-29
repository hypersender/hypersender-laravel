<?php

use Hypersender\Hypersender;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->endpoint = Config::get('hypersender-config.otp_base_url', env('HYPERSENDER_OTP_BASE_URL'));
    $this->instanceId = config('hypersender-config.otp_instance_id');
});

describe('request code', function () {
    it('can request an OTP code', function () {
        $payload = requestCodeEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/request-code" => Http::response($payload, 200),
        ]);

        $response = Hypersender::otp()->requestCode(
            '20123456789@c.us',
            6,
            true,
            false,
            false,
            'hypersender',
            1800
        );

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/request-code"
                && $request['chatId'] === '20123456789@c.us'
                && $request['length'] === 6
                && $request['useNumber'] === true
                && $request['useLetter'] === false
                && $request['allCapital'] === false
                && $request['name'] === 'hypersender'
                && $request['expires'] === 1800;
        });

        expect($response->json())->toBe($payload);
    });

    it('can request an OTP code with letters', function () {
        $payload = requestCodeEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/request-code" => Http::response($payload, 200),
        ]);

        $response = Hypersender::otp()->requestCode(
            '20123456789@c.us',
            8,
            true,
            true,
            true,
            'TestApp',
            600
        );

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/request-code"
                && $request['chatId'] === '20123456789@c.us'
                && $request['length'] === 8
                && $request['useNumber'] === true
                && $request['useLetter'] === true
                && $request['allCapital'] === true
                && $request['name'] === 'TestApp'
                && $request['expires'] === 600;
        });

        expect($response->json())->toBe($payload);
    });
});

describe('validate code', function () {
    it('can validate an OTP code', function () {
        $payload = validateCodeEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/validate-code" => Http::response($payload, 200),
        ]);

        $response = Hypersender::otp()->validateCode(
            '20123456789@c.us',
            '439713'
        );

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/validate-code"
                && $request['chatId'] === '20123456789@c.us'
                && $request['code'] === '439713';
        });

        expect($response->json())->toBe($payload);
    });

    it('validates with correct structure', function () {
        $payload = validateCodeEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/validate-code" => Http::response($payload, 200),
        ]);

        $response = Hypersender::otp()->validateCode('20123456789@c.us', '439713');

        expect($response->json())
            ->toHaveKey('success')
            ->toHaveKey('message')
            ->toHaveKey('data')
            ->and($response->json()['data'])
            ->toHaveKey('uuid')
            ->toHaveKey('chat_id')
            ->toHaveKey('status')
            ->toHaveKey('validated_at');
    });
});

describe('generate link', function () {
    it('can generate an OTP link', function () {
        $payload = generateLinkEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/generate-link" => Http::response($payload, 200),
        ]);

        $response = Hypersender::otp()->generateLink(
            '20123456789@c.us',
            1800,
            '7enkesh',
            [
                'prompt' => 'Give me link to login at 7enkesh',
                'success' => 'Here is your login link: {link}',
                'failed' => 'Login failed, please try again later.',
                'expired' => 'The link has been expired, please try another one.',
            ],
            [
                'success' => 'https://yourdomain.com/callback/success',
                'failed' => 'https://yourdomain.com/callback/failed',
            ]
        );

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/generate-link"
                && $request['chatId'] === '20123456789@c.us'
                && $request['expires'] === 1800
                && $request['name'] === '7enkesh'
                && $request['message']['prompt'] === 'Give me link to login at 7enkesh'
                && $request['message']['success'] === 'Here is your login link: {link}'
                && $request['message']['failed'] === 'Login failed, please try again later.'
                && $request['message']['expired'] === 'The link has been expired, please try another one.'
                && $request['callback']['success'] === 'https://yourdomain.com/callback/success'
                && $request['callback']['failed'] === 'https://yourdomain.com/callback/failed';
        });

        expect($response->json())->toBe($payload);
    });

    it('generates link with correct structure', function () {
        $payload = generateLinkEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/generate-link" => Http::response($payload, 200),
        ]);

        $response = Hypersender::otp()->generateLink(
            '20123456789@c.us',
            1800,
            '7enkesh',
            [
                'prompt' => 'Give me link to login at 7enkesh',
                'success' => 'Here is your login link: {link}',
                'failed' => 'Login failed, please try again later.',
                'expired' => 'The link has been expired, please try another one.',
            ],
            [
                'success' => 'https://yourdomain.com/callback/success',
                'failed' => 'https://yourdomain.com/callback/failed',
            ]
        );

        expect($response->json())
            ->toHaveKey('success')
            ->toHaveKey('message')
            ->toHaveKey('data')
            ->and($response->json()['data'])
            ->toHaveKey('uuid')
            ->toHaveKey('code')
            ->toHaveKey('chat_id')
            ->toHaveKey('status')
            ->toHaveKey('otp_type')
            ->toHaveKey('expires_at')
            ->toHaveKey('created_at')
            ->toHaveKey('whatsapp_link');
    });

    it('can generate link with different expiration times', function () {
        $payload = generateLinkEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/generate-link" => Http::response($payload, 200),
        ]);

        $response = Hypersender::otp()->generateLink(
            '20123456789@c.us',
            3600,
            'MyApp',
            [
                'prompt' => 'Request login',
                'success' => 'Link: {link}',
                'failed' => 'Failed',
                'expired' => 'Expired',
            ],
            [
                'success' => 'https://example.com/success',
                'failed' => 'https://example.com/failed',
            ]
        );

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/generate-link"
                && $request['expires'] === 3600;
        });

        expect($response->json())->toBe($payload);
    });
});
