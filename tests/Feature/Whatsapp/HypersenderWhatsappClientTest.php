<?php

use Hypersender\Hypersender;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->endpoint = Config::get('hypersender-config.whatsapp_base_url', env('HYPERSENDER_WHATSAPP_BASE_URL'));
    $this->instanceId = config('hypersender-config.whatsapp_instance_id');
});

describe('safe send text', function () {
    it('can send a text message safely with preview enabled flags', function () {
        $payload = sendTextEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-text-safe" => Http::response($payload['with_preview'], 200),
        ]);

        $response = Hypersender::whatsapp()->safeSendTextMessage('123@c.us', 'Hello', 'reply-id', true, true);

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

    it('can send a text message safely with preview disabled flags', function () {
        $payload = sendTextEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-text-safe" => Http::response($payload['without_preview'], 200),
        ]);

        $response = Hypersender::whatsapp()->safeSendTextMessage('123@c.us', 'Hello', 'reply-id', false, false);

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
});

describe('send text', function () {
    it('can send a text message with preview', function () {
        $payload = sendTextEndpointPayload();

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/send-text" => Http::response($payload['with_preview'], 200),
        ]);

        $response = Hypersender::whatsapp()->sendText('123@c.us', 'Hello', null, true, true);

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

        $response = Hypersender::whatsapp()->sendText('123@c.us', 'Hello', null, false, false);

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

        $response = Hypersender::whatsapp()->sendLinkCustomPreviewFile('123@c.us', 'Hello', null, true, 'Title', 'Description', 'https://example.com', $previewImageFile);

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

        $response = Hypersender::whatsapp()->sendLinkCustomPreviewUrl('123@c.us', 'Hello', 'reply-id', true, 'Title', 'Description', 'https://example.com', 'https://example.com/image.png');

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

        $response = Hypersender::whatsapp()->sendFileUrl('123@c.us', 'https://example.com/file.pdf', 'file.pdf', 'application/pdf', 'Caption', 'reply-id');

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-file"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'url' => 'https://example.com/file.pdf',
                    'filename' => 'file.pdf',
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

        $response = Hypersender::whatsapp()->sendFile('123@c.us', $file, 'document.pdf', 'application/pdf', 'Caption', 'reply-id');

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

        $response = Hypersender::whatsapp()->sendImageUrl('123@c.us', 'https://example.com/image.png', 'image.png', 'image/png', 'Caption', 'reply-id');

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-image"
                && $request->data() === [
                    'chatId' => '123@c.us',
                    'url' => 'https://example.com/image.png',
                    'filename' => 'image.png',
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

        $response = Hypersender::whatsapp()->sendImageFile('123@c.us', $file, 'photo.jpg', 'image/jpeg', 'Caption', 'reply-id');

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

        $response = Hypersender::whatsapp()->sendVideoUrl('123@c.us', 'https://example.com/video.mp4', true, 'Caption', 'reply-id');

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

        $response = Hypersender::whatsapp()->sendVideoFile('123@c.us', $file, true, 'Caption', 'reply-id');

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

        $response = Hypersender::whatsapp()->sendVoiceUrl('123@c.us', 'https://example.com/voice.ogg', 'Caption', 'reply-id');

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

        $response = Hypersender::whatsapp()->sendVoiceFile('123@c.us', $file, 'Caption', 'reply-id');

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

    $response = Hypersender::whatsapp()->forwardMessage('123@c.us', 'message-id');

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

    $response = Hypersender::whatsapp()->sendSeen('123@c.us', 'message-id', 'participant-id');

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

    $response = Hypersender::whatsapp()->readChat('123@c.us', 10, 2);

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

    $response = Hypersender::whatsapp()->startTyping('123@c.us');

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

    $response = Hypersender::whatsapp()->stopTyping('123@c.us');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/stop-typing"
            && $request->data() === ['chatId' => '123@c.us'];
    });

    expect($response->json())->toBe(['result' => true]);
});

it('can send a poll', function () {
    $payload = sendPollEndpointPayload();

    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/send-poll" => Http::response($payload, 200),
    ]);

    $response = Hypersender::whatsapp()->sendPoll('123@c.us', null, ['name' => 'Sample Poll', 'options' => ['Option 1', 'Option 2', 'Option 3'], 'multipleAnswers' => true]);

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-poll"
            && $request->data() === [
                'chatId' => '123@c.us',
                'reply_to' => null,
                'poll' => [
                    'name' => 'Sample Poll',
                    'options' => [
                        'Option 1',
                        'Option 2',
                        'Option 3',
                    ],
                    'multipleAnswers' => true,
                ],
            ];
    });

    expect($response->json())->toBe($payload);
});

it('can send a contact card', function () {
    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/send-contact-card" => Http::response([], 200),
    ]);

    $response = Hypersender::whatsapp()->sendContactCard('123@c.us', ['vcard' => 'vcard', 'fullName' => 'Full Name', 'organization' => 'Organization', 'phoneNumber' => '1234567890', 'whatsappId' => '1234567890']);

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-contact-card"
            && $request->data() === [
                'chatId' => '123@c.us',
                'contacts' => [
                    'vcard' => 'vcard',
                    'fullName' => 'Full Name',
                    'organization' => 'Organization',
                    'phoneNumber' => '1234567890',
                    'whatsappId' => '1234567890',
                ],
            ];
    });

    expect($response->json())->toBe([]);
});

it('can send a location', function () {
    $payload = sendLocationEndpointPayload();

    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/send-location" => Http::response($payload, 200),
    ]);

    $response = Hypersender::whatsapp()->sendLocation('123@c.us', 38.8937255, -77.0969763, 'Washington, D.C.', 'reply-id');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/send-location"
            && $request->data() === [
                'chatId' => '123@c.us',
                'latitude' => 38.8937255,
                'longitude' => -77.0969763,
                'title' => 'Washington, D.C.',
                'reply_to' => 'reply-id',
            ];
    });

    expect($response->json())->toBe($payload);
});

it('can react to a message', function () {
    $payload = reactToMessageEndpointPayload();

    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/react-to-message" => Http::response($payload, 200),
    ]);

    $response = Hypersender::whatsapp()->reactToMessage('message-id', '👍');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/react-to-message"
            && $request->data() === ['messageId' => 'message-id', 'reaction' => '👍'];
    });

    expect($response->json())->toBe($payload);
});

it('can star a message', function () {
    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/star-message" => Http::response([], 200),
    ]);

    $response = Hypersender::whatsapp()->star('123@c.us', 'message-id', true);

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/star-message"
            && $request->data() === ['chatId' => '123@c.us', 'messageId' => 'message-id', 'star' => true];
    });

    expect($response->json())->toBe([]);
});

it('can delete a message', function () {
    $payload = deleteMessageEndpointPayload();

    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/delete-message?chatId=123%40c.us&messageId=message-id" => Http::response($payload, 200),
    ]);

    $response = Hypersender::whatsapp()->deleteMessage('123@c.us', 'message-id');

    Http::assertSent(function (Request $request) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/delete-message?chatId=123%40c.us&messageId=message-id"
            && $request->data() === [];
    });

    expect($response->json())->toBe($payload);
});

it('can get a queued request', function () {
    $payload = queuedRequestEndpointPayload();

    $uuid = '550e8400-e29b-41d4-a716-446655440000';

    Http::fake([
        "{$this->endpoint}/{$this->instanceId}/queued-requests/{$uuid}" => Http::response($payload, 200),
    ]);

    $response = Hypersender::whatsapp()->getQueuedRequest($uuid);

    Http::assertSent(function (Request $request) use ($uuid) {
        return $request->url() === "{$this->endpoint}/{$this->instanceId}/queued-requests/{$uuid}";
    });

    expect($response->json())->toBe($payload);
});

describe('lids', function () {
    it('can get all known lids', function () {
        $payload = [['lid' => '1111111@lid', 'pn' => '3333333@c.us']];

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/contacts/get-all-known-lids?limit=50&offset=10" => Http::response($payload, 200),
        ]);

        $response = Hypersender::whatsapp()->getAllKnownLids(50, 10);

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && $request->url() === "{$this->endpoint}/{$this->instanceId}/contacts/get-all-known-lids?limit=50&offset=10";
        });

        expect($response->json())->toBe($payload);
    });

    it('omits pagination when not given', function () {
        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/contacts/get-all-known-lids" => Http::response([], 200),
        ]);

        Hypersender::whatsapp()->getAllKnownLids();

        Http::assertSent(function (Request $request) {
            return $request->url() === "{$this->endpoint}/{$this->instanceId}/contacts/get-all-known-lids";
        });
    });

    it('can get the lids count', function () {
        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/contacts/get-lids-count" => Http::response(['count' => 123], 200),
        ]);

        $response = Hypersender::whatsapp()->getLidsCount();

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && $request->url() === "{$this->endpoint}/{$this->instanceId}/contacts/get-lids-count";
        });

        expect($response->json())->toBe(['count' => 123]);
    });

    it('can get a phone number by lid', function () {
        $payload = ['lid' => '1111111@lid', 'pn' => '3333333@c.us'];

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/contacts/get-phone-number-by-lid*" => Http::response($payload, 200),
        ]);

        $response = Hypersender::whatsapp()->getPhoneNumberByLid('1111111@lid');

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && $request->url() === "{$this->endpoint}/{$this->instanceId}/contacts/get-phone-number-by-lid?lid=1111111%40lid";
        });

        expect($response->json())->toBe($payload);
    });

    it('can get a lid by phone number', function () {
        $payload = ['lid' => '1111111@lid', 'pn' => '3333333@c.us'];

        Http::fake([
            "{$this->endpoint}/{$this->instanceId}/contacts/get-lid-by-phone-number*" => Http::response($payload, 200),
        ]);

        $response = Hypersender::whatsapp()->getLidByPhoneNumber('3333333@c.us');

        Http::assertSent(function (Request $request) {
            return $request->method() === 'GET'
                && $request->url() === "{$this->endpoint}/{$this->instanceId}/contacts/get-lid-by-phone-number?phoneNumber=3333333%40c.us";
        });

        expect($response->json())->toBe($payload);
    });
});
