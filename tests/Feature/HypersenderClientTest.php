<?php

use Hypersender\Hypersender\Facades\Hypersender;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->endpoint = config('hypersender-laravel.base_url');
    $this->instanceId = config('hypersender-laravel.instance_id');
});

describe('send text', function () {
    it('can send a safe text message with preview', function () {
        $payload = sendTextEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-text-safe" => Http::response($payload['with_preview'], 200),
        ]);

        $response = Hypersender::sendTextSafe('123@c.us', 'Hello', 'reply-id', true, true);

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-text-safe"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'text' => 'Hello',
                    'reply_to' => 'reply-id',
                    'link_preview' => true,
                    'link_preview_high_quality' => true,
                ];
        });

        expect($response->json())->toBe($payload['with_preview']);
    });

    it('can send a safe text message without preview', function () {
        $payload = sendTextEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-text-safe" => Http::response($payload['without_preview'], 200),
        ]);

        $response = Hypersender::sendTextSafe('123@c.us', 'Hello', 'reply-id', false, false);

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-text-safe"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'text' => 'Hello',
                    'reply_to' => 'reply-id',
                    'link_preview' => false,
                    'link_preview_high_quality' => false,
                ];
        });

        expect($response->json())->toBe($payload['without_preview']);
    });

    it('can send a text message with preview', function () {
        $payload = sendTextEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-text" => Http::response($payload['with_preview'], 200),
        ]);

        $response = Hypersender::sendText('123@c.us', 'Hello', null, true, true);

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-text"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'text' => 'Hello',
                    'reply_to' => null,
                    'link_preview' => true,
                    'link_preview_high_quality' => true,
                ];
        });

        expect($response->json())->toBe($payload['with_preview']);
    });

    it('can send a text message without preview', function () {
        $payload = sendTextEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-text" => Http::response($payload['without_preview'], 200),
        ]);

        $response = Hypersender::sendText('123@c.us', 'Hello', null, false, false);

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-text"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'text' => 'Hello',
                    'reply_to' => null,
                    'link_preview' => false,
                    'link_preview_high_quality' => false,
                ];
        });

        expect($response->json())->toBe($payload['without_preview']);
    });
});

describe('send link custom preview', function () {
    it('can send a link custom preview by providing the image it self as a file upload', function () {
        $payload = sendLinkCustomPreviewPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-link-custom-preview" => Http::response($payload, 200),
        ]);

        $previewImageFile = UploadedFile::fake()->image('test.png');

        $response = Hypersender::sendLinkCustomPreviewFile('123@c.us', 'Hello', null, true, 'Title', 'Description', 'https://example.com', $previewImageFile);

        Http::assertSent(function (Request $request) use ($previewImageFile) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-link-custom-preview"
                && $request->hasFile('preview_image_file', $previewImageFile);
        });

        expect($response->json())->toBe($payload);
    });

    it('can send a link custom preview by providing a URL for the image', function () {
        $payload = sendLinkCustomPreviewPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-link-custom-preview" => Http::response($payload, 200),
        ]);

        $response = Hypersender::sendLinkCustomPreviewUrl('123@c.us', 'Hello', 'reply-id', true, 'Title', 'Description', 'https://example.com', 'https://example.com/image.png');

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-link-custom-preview"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'text' => 'Hello',
                    'reply_to' => 'reply-id',
                    'high_quality' => true,
                    'preview_title' => 'Title',
                    'preview_description' => 'Description',
                    'preview_url' => 'https://example.com',
                    'preview_image_url' => 'https://example.com/image.png',
                ];
        });

        expect($response->json())->toBe($payload);
    });
});

describe('send file', function () {
    it('can send a file by providing a URL', function () {
        $payload = sendFileEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-file" => Http::response($payload, 200),
        ]);

        $response = Hypersender::sendFileUrl('123@c.us', 'https://example.com/file.pdf', 'file.pdf', 'application/pdf', 'Caption', 'reply-id');

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-file"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'url' => 'https://example.com/file.pdf',
                    'fileName' => 'file.pdf',
                    'mimetype' => 'application/pdf',
                    'caption' => 'Caption',
                    'reply_to' => 'reply-id',
                ];
        });

        expect($response->json())->toBe($payload);
    });

    it('can send a file by providing the file itself as a file upload', function () {
        $payload = sendFileEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-file" => Http::response($payload, 200),
        ]);

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = Hypersender::sendFile('123@c.us', $file, 'document.pdf', 'application/pdf', 'Caption', 'reply-id');

        Http::assertSent(function (Request $request) use ($file) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-file"
                && $request->hasFile('file', $file);
        });

        expect($response->json())->toBe($payload);
    });
});

describe('send image', function () {
    it('can send an image by providing a URL', function () {
        $payload = sendImageEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-image" => Http::response($payload, 200),
        ]);

        $response = Hypersender::sendImageUrl('123@c.us', 'https://example.com/image.png', 'image.png', 'image/png', 'Caption', 'reply-id');

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-image"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'url' => 'https://example.com/image.png',
                    'fileName' => 'image.png',
                    'mimetype' => 'image/png',
                    'caption' => 'Caption',
                    'reply_to' => 'reply-id',
                ];
        });

        expect($response->json())->toBe($payload);
    });

    it('can send an image by providing the file itself as a file upload', function () {
        $payload = sendImageEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-image" => Http::response($payload, 200),
        ]);

        $file = UploadedFile::fake()->image('photo.jpg');

        $response = Hypersender::sendImageFile('123@c.us', $file, 'photo.jpg', 'image/jpeg', 'Caption', 'reply-id');

        Http::assertSent(function (Request $request) use ($file) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-image"
                && $request->hasFile('file', $file);
        });

        expect($response->json())->toBe($payload);
    });
});

describe('send video', function () {
    it('can send a video by providing a URL', function () {
        $payload = sendVideoEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-video" => Http::response($payload, 200),
        ]);

        $response = Hypersender::sendVideoUrl('123@c.us', 'https://example.com/video.mp4', true, 'Caption', 'reply-id');

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-video"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'url' => 'https://example.com/video.mp4',
                    'asNote' => true,
                    'caption' => 'Caption',
                    'reply_to' => 'reply-id',
                ];
        });

        expect($response->json())->toBe($payload);
    });

    it('can send a video by providing the file itself as a file upload', function () {
        $payload = sendVideoEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-video" => Http::response($payload, 200),
        ]);

        $file = UploadedFile::fake()->create('video.mp4', 1000);

        $response = Hypersender::sendVideoFile('123@c.us', $file, true, 'Caption', 'reply-id');

        Http::assertSent(function (Request $request) use ($file) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-video"
                && $request->hasFile('file', $file);
        });

        expect($response->json())->toBe($payload);
    });
});

describe('send voice', function () {
    it('can send a voice message by providing a URL', function () {
        $payload = sendAudioEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-voice" => Http::response($payload, 200),
        ]);

        $response = Hypersender::sendVoiceUrl('123@c.us', 'https://example.com/voice.ogg', 'Caption', 'reply-id');

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-voice"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'url' => 'https://example.com/voice.ogg',
                    'caption' => 'Caption',
                    'reply_to' => 'reply-id',
                ];
        });

        expect($response->json())->toBe($payload);
    });

    it('can send a voice message by providing the file itself as a file upload', function () {
        $payload = sendAudioEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-voice" => Http::response($payload, 200),
        ]);

        $file = UploadedFile::fake()->create('voice.ogg', 100);

        $response = Hypersender::sendVoiceFile('123@c.us', $file, 'Caption', 'reply-id');

        Http::assertSent(function (Request $request) use ($file) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-voice"
                && $request->hasFile('file', $file);
        });

        expect($response->json())->toBe($payload);
    });
});

it('can forward a message', function () {
    $payload = forwardMessageEndpointPayload();

    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/forward-message" => Http::response($payload, 200),
    ]);

    $response = Hypersender::forwardMessage('123@c.us', 'message-id');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/forward-message"
            && $request->data() === [
                'chatId' => '123@c.us',
                'messageId' => 'message-id',
            ];
    });

    expect($response->json())->toBe($payload);
});

it('can send a seen receipt', function () {
    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/send-seen" => Http::response([], 200),
    ]);

    $response = Hypersender::sendSeen('123@c.us', 'message-id', 'participant-id');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-seen"
            && $request->data() === [
                'chatId' => '123@c.us',
                'messageId' => 'message-id',
                'participant' => 'participant-id',
            ];
    });

    expect($response->json())->toBe([]);
});

it('can read a chat', function () {
    $payload = readChatsEndpointPayload();

    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/read-chat?messages=10&days=2" => Http::response($payload, 200),
    ]);

    $response = Hypersender::readChat('123@c.us', 10, 2);

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/read-chat?messages=10&days=2"
            && $request->data() === ['chatId' => '123@c.us'];
    });

    expect($response->json())->toBe($payload);
});

it('can start typing', function () {
    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/start-typing" => Http::response(['result' => true], 200),
    ]);

    $response = Hypersender::startTyping('123@c.us');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/start-typing"
            && $request->data() === ['chatId' => '123@c.us'];
    });

    expect($response->json())->toBe(['result' => true]);
});

it('can stop typing', function () {
    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/stop-typing" => Http::response(['result' => true], 200),
    ]);

    $response = Hypersender::stopTyping('123@c.us');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/stop-typing"
            && $request->data() === ['chatId' => '123@c.us'];
    });

    expect($response->json())->toBe(['result' => true]);
});
