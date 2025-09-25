<?php

namespace Hypersender\Hypersender;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

abstract class AbstractClient
{
    protected string $baseUrl;

    protected ?string $apiKey;

    protected ?string $instanceId;

    public function __construct()
    {
        $this->baseUrl = Config::get('hypersender-laravel.base_url', env('HYPERSENDER_BASE_URL'));
        $this->apiKey = Config::get('hypersender-laravel.api_key', env('HYPERSENDER_API_KEY'));
        $this->instanceId = Config::get('hypersender-laravel.instance_id', env('HYPERSENDER_INSTANCE_ID'));
    }

    protected function request(string $contentType = 'application/json'): PendingRequest
    {
        $headers = [
            'Authorization' => 'Bearer '.$this->apiKey,
            'User-Agent' => 'Hypersender Laravel SDK',
            'Accept' => 'application/json',
            'Content-Type' => $contentType,
        ];

        return Http::baseUrl($this->baseUrl)
            ->withHeaders($headers);
    }

    protected function post(string $uri, array $payload): Response
    {
        return $this->request()->asJson()->post($this->endpoint($uri), $payload);
    }

    protected function postMultipart(string $uri, array $payload, string $fileField): Response
    {
        return $this->request('multipart/form-data')->asMultipart()->post($this->endpoint($uri), $payload, $fileField);
    }

    protected function get(string $uri, array $query = []): Response
    {
        return $this->request()->asJson()->get($this->endpoint($uri), $query);
    }

    protected function put(string $uri, array $payload): Response
    {
        return $this->request()->asJson()->put($this->endpoint($uri), $payload);
    }

    protected function delete(string $uri, array $payload): Response
    {
        return $this->request()->asJson()->delete($this->endpoint($uri), $payload);
    }

    protected function endpoint(string $uri): string
    {
        if (is_null($this->instanceId) || $this->instanceId === '') {
            throw new \InvalidArgumentException('Hypersender instance_id is required but missing.');
        }

        $normalizedUri = str_starts_with($uri, '/') ? $uri : '/'.$uri;

        return "/{$this->instanceId}{$normalizedUri}";
    }
}
