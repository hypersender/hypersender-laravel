<?php

namespace Hypersender\Hypersender\Clients\Sms;

use Hypersender\Hypersender\AbstractClient;
use Illuminate\Http\Client\Response;

class HypersenderSmsClient extends AbstractClient
{
    public function sendText(string $to, string $text): Response
    {
        return $this->post('/sms/send-text', [
            'to' => $to,
            'text' => $text,
        ]);
    }
}
