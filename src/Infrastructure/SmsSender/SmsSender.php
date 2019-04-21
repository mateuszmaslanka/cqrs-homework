<?php declare(strict_types=1);

namespace App\Infrastructure\SmsSender;

use App\Application\Service\SmsSender\SmsSenderInterface;

class SmsSender implements SmsSenderInterface
{
    public function send(int $phone, string $message): void
    {
        // TODO: send sms
        echo sprintf(
            'Send SMS "%s" to phone "%s".',
            $message,
            $phone
        );
    }
}
