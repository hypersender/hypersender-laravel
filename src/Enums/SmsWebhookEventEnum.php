<?php

namespace Hypersender\Enums;

use Hypersender\Events\Sms\MessageCallMissed;
use Hypersender\Events\Sms\MessageNotificationScheduled;
use Hypersender\Events\Sms\MessagePhoneDelivered;
use Hypersender\Events\Sms\MessagePhoneReceived;
use Hypersender\Events\Sms\MessagePhoneSent;
use Hypersender\Events\Sms\MessageSendExpired;
use Hypersender\Events\Sms\MessageSendFailed;
use Hypersender\Events\Sms\PhoneHeartbeatDisabled;
use Hypersender\Events\Sms\PhoneHeartbeatMissed;
use Hypersender\Events\Sms\PhoneHeartbeatOffline;
use Hypersender\Events\Sms\PhoneHeartbeatOnline;
use Hypersender\Events\Sms\PhoneHeartbeatReceived;

enum SmsWebhookEventEnum: string
{
    case PHONE_HEARTBEAT_RECEIVED = 'phone.heartbeat.received';
    case PHONE_HEARTBEAT_ONLINE = 'phone.heartbeat.online';
    case PHONE_HEARTBEAT_OFFLINE = 'phone.heartbeat.offline';
    case PHONE_HEARTBEAT_MISSED = 'phone.heartbeat.missed';
    case PHONE_HEARTBEAT_DISABLED = 'phone.heartbeat.disabled';
    case MESSAGE_SEND_FAILED = 'message.send.failed';
    case MESSAGE_PHONE_DELIVERED = 'message.phone.delivered';
    case MESSAGE_PHONE_RECEIVED = 'message.phone.received';
    case MESSAGE_NOTIFICATION_SCHEDULED = 'message.notification.scheduled';
    case MESSAGE_PHONE_SENT = 'message.phone.sent';
    case MESSAGE_SEND_EXPIRED = 'message.send.expired';
    case MESSAGE_CALL_MISSED = 'message.call.missed';

    public function eventClass(): string
    {
        return match ($this) {
            self::PHONE_HEARTBEAT_RECEIVED => PhoneHeartbeatReceived::class,
            self::PHONE_HEARTBEAT_ONLINE => PhoneHeartbeatOnline::class,
            self::PHONE_HEARTBEAT_OFFLINE => PhoneHeartbeatOffline::class,
            self::PHONE_HEARTBEAT_MISSED => PhoneHeartbeatMissed::class,
            self::PHONE_HEARTBEAT_DISABLED => PhoneHeartbeatDisabled::class,
            self::MESSAGE_SEND_FAILED => MessageSendFailed::class,
            self::MESSAGE_PHONE_DELIVERED => MessagePhoneDelivered::class,
            self::MESSAGE_PHONE_RECEIVED => MessagePhoneReceived::class,
            self::MESSAGE_NOTIFICATION_SCHEDULED => MessageNotificationScheduled::class,
            self::MESSAGE_PHONE_SENT => MessagePhoneSent::class,
            self::MESSAGE_SEND_EXPIRED => MessageSendExpired::class,
            self::MESSAGE_CALL_MISSED => MessageCallMissed::class,
        };
    }
}
