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

    protected function request(): PendingRequest
    {
        $headers = [
            'Authorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json; charset=utf-8',
            'User-Agent' => 'Hypersender Laravel SDK',
            'Accept' => 'application/json',
        ];

        return Http::baseUrl($this->baseUrl)
            ->withHeaders($headers)
            ->withQueryParameters([
                'instance_id' => $this->instanceId,
            ])
            ->asJson();
    }

    protected function post(string $uri, array $payload): Response
    {
        return $this->request()->post($uri, $payload);
    }

    protected function get(string $uri, array $query = []): Response
    {
        return $this->request()->get($uri, $query);
    }

    protected function put(string $uri, array $payload): Response
    {
        return $this->request()->put($uri, $payload);
    }

    protected function delete(string $uri, array $payload): Response
    {
        return $this->request()->delete($uri, $payload);
    }
}
