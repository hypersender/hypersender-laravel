<?php

namespace Hypersender\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Exception thrown when the Hypersender API returns an error response.
 */
class HypersenderApiErrorException extends Exception
{
    /**
     * Raw response body returned by Hypersender API.
     */
    protected string $responseBody;

    /**
     * Create a new Hypersender API error exception.
     */
    public function __construct(string $message = 'Hypersender API error', int $code = 0, ?\Throwable $previous = null, string $responseBody = '')
    {
        $this->responseBody = $responseBody;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Factory method to create an exception from a response body.
     */
    public static function fromResponseBody(string $body): self
    {
        $msg = 'Hypersender API error';

        if ($body !== '') {
            $msg .= ": {$body}";
        }

        return new self($msg, $body);
    }

    /**
     * Optionally render a JSON response when the request expects JSON.
     */
    public function render(Request $request): Response|SymfonyResponse|bool
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Hypersender API error',
                'error' => $this->responseBody,
            ], 422);
        }

        return false; // fall back to default rendering for non-JSON
    }
}
