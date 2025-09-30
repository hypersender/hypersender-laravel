<?php

namespace Hypersender\Hypersender\Http\Requests\Sms;

/**
 * See documentation for available parameters:
 *
 * @link https://docs.hypersender.com/docs/sms/heartbeats
 */
class HeartbeatsIndexRequest
{
    public function __construct(
        public readonly ?string $perPage = null,
        public readonly ?string $appVersion = null,
        public readonly ?string $batteryLevel = null,
        public readonly ?string $isCharging = null,
        public readonly ?string $localTimestamp = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $sort = null,
        public readonly ?string $include = null,
        public readonly ?string $page = null,
    ) {}

    public function toQuery(): array
    {
        return self::removeNullsDeep([
            'per_page' => $this->perPage,
            'filter' => [
                'app_version' => $this->appVersion,
                'battery_level' => $this->batteryLevel,
                'is_charging' => $this->isCharging,
                'local_timestamp' => $this->localTimestamp,
                'created_at' => $this->createdAt,
            ],
            'sort' => $this->sort,
            'include' => $this->include,
            'page' => $this->page,
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
