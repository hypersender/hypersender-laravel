<?php

namespace Hypersender\Clients\Enterprise;

use Hypersender\AbstractClient;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;

/**
 * Manage WhatsApp instances on an Enterprise account.
 * Unlike the other clients, the instance is passed per call instead of being set in config.
 */
class EnterpriseClient extends AbstractClient
{
    public function __construct()
    {
        $baseUrl = Config::get('hypersender-config.enterprise_base_url', env('HYPERSENDER_ENTERPRISE_BASE_URL'));
        $apiKey = Config::get('hypersender-config.enterprise_api_key', env('HYPERSENDER_ENTERPRISE_API_KEY'));

        parent::__construct($baseUrl, $apiKey, null);
    }

    /**
     * List instances
     *
     * @param  array  $filters  Any of: uuid, name (partial match), phone,
     *                          status (STOPPED, STARTING, WORKING, SCAN_QR_CODE, FAILED, PENDING)
     */
    public function listInstances(array $filters = []): Response
    {
        return $this->get('/instances', array_filter(
            ['filter' => $filters],
            fn ($value) => $value !== [],
        ));
    }

    /**
     * Create a WhatsApp instance
     *
     * @param  string  $phone  E.164 phone number (e.g. "+201234567890")
     * @param  string  $name  Instance name, max 50 characters, unique per account
     * @param  string|null  $webhookUrl  Optional https webhook URL
     */
    public function createInstance(string $phone, string $name, ?string $webhookUrl = null): Response
    {
        return $this->post('/create-instance', array_filter([
            'phone' => $phone,
            'name' => $name,
            'webhook_url' => $webhookUrl,
        ], fn ($value) => $value !== null));
    }

    /**
     * Check an instance's health
     *
     * @param  string  $instanceId  Instance UUID
     */
    public function getInstanceHealth(string $instanceId): Response
    {
        return $this->get("/{$instanceId}/health");
    }

    /**
     * Request a new QR code to connect an instance
     *
     * @param  string  $instanceId  Instance UUID
     */
    public function requestNewQrCode(string $instanceId): Response
    {
        return $this->post("/{$instanceId}/request-new-qr-code", []);
    }

    /**
     * Restart an instance
     *
     * @param  string  $instanceId  Instance UUID
     */
    public function restartInstance(string $instanceId): Response
    {
        return $this->post("/{$instanceId}/restart", []);
    }

    protected function endpoint(string $uri): string
    {
        return str_starts_with($uri, '/') ? $uri : '/'.$uri;
    }
}
