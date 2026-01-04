<?php

namespace Hypersender\Clients\Otp;

use Hypersender\AbstractClient;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Config;

class OtpClient extends AbstractClient
{
    public function __construct()
    {
        $baseUrl = Config::get('hypersender-config.otp_base_url', env('HYPERSENDER_OTP_BASE_URL'));
        $apiKey = Config::get('hypersender-config.otp_api_key', env('HYPERSENDER_OTP_API_KEY'));
        $instanceId = Config::get('hypersender-config.otp_instance_id', env('HYPERSENDER_OTP_INSTANCE_ID'));

        parent::__construct($baseUrl, $apiKey, $instanceId);
    }

    /**
     * Request an OTP code to be sent to a chat
     *
     * @param  string  $chatId  The chat ID to send the OTP to
     * @param  int  $length  Length of the OTP code (min: 4, max: 10)
     * @param  bool  $useNumber  Whether to use numbers in the OTP
     * @param  bool  $useLetter  Whether to use letters in the OTP
     * @param  bool  $allCapital  Whether all letters should be capital
     * @param  string  $name  Company/service name to include in the message
     * @param  int  $expires  Expiration time in seconds (min: 60, max: 1800)
     */
    public function requestCode(
        string $chatId,
        int $length,
        bool $useNumber,
        bool $useLetter,
        bool $allCapital,
        string $name,
        int $expires,
    ): Response {
        return $this->post('/request-code', [
            'chatId' => $chatId,
            'length' => $length,
            'useNumber' => $useNumber,
            'useLetter' => $useLetter,
            'allCapital' => $allCapital,
            'name' => $name,
            'expires' => $expires,
        ]);
    }

    /**
     * Validate an OTP code
     *
     * @param  string  $chatId  The chat ID that the OTP was sent to
     * @param  string  $code  The OTP code to validate
     */
    public function validateCode(
        string $chatId,
        string $code,
    ): Response {
        return $this->post('/validate-code', [
            'chatId' => $chatId,
            'code' => $code,
        ]);
    }

    /**
     * Generate an OTP link for WhatsApp instances
     *
     * @param  string  $chatId  The chat ID to associate with the OTP
     * @param  int  $expires  Expiration time in seconds (min: 60, max: 3600)
     * @param  string  $name  Company/service name
     * @param  array  $message  Message templates with keys: prompt, success, failed, expired
     * @param  array  $callback  Callback URLs with keys: success, failed
     */
    public function generateLink(
        string $chatId,
        int $expires,
        string $name,
        array $message,
        array $callback,
    ): Response {
        return $this->post('/generate-link', [
            'chatId' => $chatId,
            'expires' => $expires,
            'name' => $name,
            'message' => $message,
            'callback' => $callback,
        ]);
    }
}
