<?php

namespace Hypersender\Hypersender\Http\Requests\Sms;

/**
 * See documentation for available parameters:
 *
 * @link https://docs.hypersender.com/docs/sms/message-threads
 */
class MessageThreadsIndexRequest
{
    public function __construct(
        public readonly ?string $perPage = null,
        public readonly ?string $sort = null,
        public readonly ?string $include = null,
        public readonly ?string $lastMessageContent = null,
        public readonly ?bool $isArchived = null,
        public readonly ?string $lastMessageId = null,
        public readonly ?string $fromOrToNumber = null,
    ) {}

    public function toQuery(): array
    {
        return self::removeNullsDeep([
            'per_page' => $this->perPage,
            'sort' => $this->sort,
            'include' => $this->include,
            'filter' => [
                'last_message_content' => $this->lastMessageContent,
                'is_archived' => $this->isArchived,
                'last_message_id' => $this->lastMessageId,
                'from_or_to_number' => $this->fromOrToNumber,
            ],
        ]);
    }

    private static function removeNullsDeep(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::removeNullsDeep($value);
                if ($data[$key] === []) {
                    unset($data[$key]);
                }
            } elseif ($value === null) {
                unset($data[$key]);
            }
        }

        return $data;
    }
}
