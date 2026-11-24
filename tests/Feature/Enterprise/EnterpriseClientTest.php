<?php

use Hypersender\Hypersender;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->endpoint = config('hypersender-config.enterprise_base_url');
    $this->instance = [
        'id' => 'c9a1dd76-5678-4f9d-9012-a5b78de100c3',
        'name' => 'MyInstanceName',
        'phone' => '+201234567890',
        'service_type' => 'whatsapp',
        'status' => 'SCAN_QR_CODE',
    ];
});

it('sends the enterprise api key', function () {
    Http::fake(["{$this->endpoint}/instances" => Http::response([], 200)]);

    Hypersender::enterprise()->listInstances();

    Http::assertSent(fn (Request $request) => $request->hasHeader('Authorization', 'Bearer test-enterprise-key'));
});

it('can list instances', function () {
    Http::fake(["{$this->endpoint}/instances" => Http::response([$this->instance], 200)]);

    $response = Hypersender::enterprise()->listInstances();

    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && $request->url() === "{$this->endpoint}/instances");

    expect($response->json())->toBe([$this->instance]);
});

it('can filter instances', function () {
    $url = "{$this->endpoint}/instances?filter%5Bstatus%5D=WORKING&filter%5Bname%5D=shop";

    Http::fake([$url => Http::response([], 200)]);

    Hypersender::enterprise()->listInstances(['status' => 'WORKING', 'name' => 'shop']);

    Http::assertSent(fn (Request $request) => $request->url() === $url);
});

it('can create an instance', function () {
    Http::fake(["{$this->endpoint}/create-instance" => Http::response($this->instance, 201)]);

    $response = Hypersender::enterprise()->createInstance('+201234567890', 'MyInstanceName', 'https://example.com/hook');

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === "{$this->endpoint}/create-instance"
        && $request->data() === ['phone' => '+201234567890', 'name' => 'MyInstanceName', 'webhook_url' => 'https://example.com/hook']);

    expect($response->json())->toBe($this->instance);
});

it('omits the webhook url when creating without one', function () {
    Http::fake(["{$this->endpoint}/create-instance" => Http::response($this->instance, 201)]);

    Hypersender::enterprise()->createInstance('+201234567890', 'MyInstanceName');

    Http::assertSent(fn (Request $request) => $request->data() === ['phone' => '+201234567890', 'name' => 'MyInstanceName']);
});

it('can check instance health', function () {
    $payload = ['instance_id' => $this->instance['id'], 'status' => 'WORKING'];

    Http::fake(["{$this->endpoint}/{$this->instance['id']}/health" => Http::response($payload, 200)]);

    $response = Hypersender::enterprise()->getInstanceHealth($this->instance['id']);

    Http::assertSent(fn (Request $request) => $request->method() === 'GET'
        && $request->url() === "{$this->endpoint}/{$this->instance['id']}/health");

    expect($response->json())->toBe($payload);
});

it('can request a new qr code', function () {
    Http::fake(["{$this->endpoint}/{$this->instance['id']}/request-new-qr-code" => Http::response($this->instance, 200)]);

    $response = Hypersender::enterprise()->requestNewQrCode($this->instance['id']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === "{$this->endpoint}/{$this->instance['id']}/request-new-qr-code");

    expect($response->json())->toBe($this->instance);
});

it('can restart an instance', function () {
    $payload = ['message' => 'WhatsApp instance restarted successfully.'];

    Http::fake(["{$this->endpoint}/{$this->instance['id']}/restart" => Http::response($payload, 200)]);

    $response = Hypersender::enterprise()->restartInstance($this->instance['id']);

    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && $request->url() === "{$this->endpoint}/{$this->instance['id']}/restart");

    expect($response->json())->toBe($payload);
});
