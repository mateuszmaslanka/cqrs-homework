<?php declare(strict_types=1);

namespace App\Application\Service\SmsSender;

interface SmsSenderInterface
{
    /**
     * @throws SmsSenderException
     */
    public function send(int $phone, string $message): void;
}
