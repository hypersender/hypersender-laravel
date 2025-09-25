<?php

use Hypersender\Hypersender\Facades\Hypersender;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->endpoint = config('hypersender-laravel.base_url');
    $this->instanceId = config('hypersender-laravel.instance_id');
});

it('can send a text message', function () {
    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/send-text" => Http::response(['message' => 'sent'], 200),
    ]);

    Hypersender::sendText('123@c.us', 'Hello', null, true, true);

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-text"
            && $request->data() === ['chatId' => '123@c.us', 'text' => 'Hello', 'reply_to' => null, 'link_preview' => true, 'link_preview_high_quality' => true];
    });
});

it('can send a link custom preview by providing the image it self as a file upload', function () {
    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/send-link-custom-preview" => Http::response(['message' => 'sent'], 200),
    ]);

    $previewImageFile = UploadedFile::fake()->image('test.png');

    Hypersender::sendLinkCustomPreviewFile('123@c.us', 'Hello', null, true, 'Title', 'Description', 'https://example.com', $previewImageFile);

    Http::assertSent(function (Request $request) use ($previewImageFile) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-link-custom-preview"
            && $request->hasFile('preview_image_file', $previewImageFile);
    });
});

// TODO: Add more tests for the other methods
