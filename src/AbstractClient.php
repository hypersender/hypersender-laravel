<?php

namespace Hypersender;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class AbstractClient
{
    public function __construct(
        protected string $baseUrl,
        protected ?string $apiKey,
        protected ?string $instanceId,
    ) {}

    protected function request(string $contentType = 'application/json', array $query = []): PendingRequest
    {
        $headers = [
            'Authorization' => 'Bearer '.$this->apiKey,
            'User-Agent' => 'Hypersender Laravel SDK',
            'Accept' => 'application/json',
            'Content-Type' => $contentType,
        ];

        return Http::baseUrl($this->baseUrl)
            ->withQueryParameters($query)
            ->withHeaders($headers);
    }

    protected function post(string $uri, array $payload, ?array $query = []): Response
    {
        return $this->request('application/json', $query)->asJson()->post($this->endpoint($uri), $payload);
    }

    protected function postMultipart(string $uri, array $payload, string $fileField, ?array $query = []): Response
    {
        return $this->request('multipart/form-data', $query)->asMultipart()->post($this->endpoint($uri), $payload, $fileField);
    }

    protected function get(string $uri, ?array $query = []): Response
    {
        return $this->request('application/json', $query)->asJson()->get($this->endpoint($uri), $query);
    }

    protected function put(string $uri, array $payload, ?array $query = []): Response
    {
        return $this->request('application/json', $query)->asJson()->put($this->endpoint($uri), $payload);
    }

    protected function delete(string $uri, array $payload, ?array $query = []): Response
    {
        return $this->request('application/json', $query)->asJson()->delete($this->endpoint($uri), $payload);
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
