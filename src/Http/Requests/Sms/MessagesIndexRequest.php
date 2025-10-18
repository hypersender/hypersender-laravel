<?php

namespace Hypersender\Http\Requests\Sms;

use Hypersender\Support\RemovesNullsDeep;

/**
 * See documentation for available parameters:
 *
 * @link https://docs.hypersender.com/docs/sms/index-messages
 */
class MessagesIndexRequest
{
    use RemovesNullsDeep;

    public function __construct(
        public readonly ?string $perPage = null,
        public readonly ?string $requestId = null,
        public readonly ?string $messageThreadId = null,
        public readonly ?string $fromPhoneNumber = null,
        public readonly ?string $toPhoneNumber = null,
        public readonly ?string $content = null,
        public readonly ?string $status = null,
        public readonly ?string $type = null,
        public readonly ?string $deliveredAt = null,
        public readonly ?string $receivedAt = null,
        public readonly ?string $expiredAt = null,
        public readonly ?string $failedAt = null,
        public readonly ?string $scheduleSendAt = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
        public readonly ?string $sort = null,
        public readonly ?string $include = null,
        public readonly ?string $page = null,
    ) {}

    public function toQuery(): array
    {
        return self::removeNullsDeep([
            'per_page' => $this->perPage,
            'filter' => [
                'request_id' => $this->requestId,
                'message_thread_id' => $this->messageThreadId,
                'from_phone_number' => $this->fromPhoneNumber,
                'to_phone_number' => $this->toPhoneNumber,
                'content' => $this->content,
                'status' => $this->status,
                'type' => $this->type,
                'delivered_at' => $this->deliveredAt,
                'received_at' => $this->receivedAt,
                'expired_at' => $this->expiredAt,
                'failed_at' => $this->failedAt,
                'schedule_send_at' => $this->scheduleSendAt,
                'created_at' => $this->createdAt,
                'updated_at' => $this->updatedAt,
            ],
            'sort' => $this->sort,
            'include' => $this->include,
            'page' => $this->page,
        ]);
    }
}
