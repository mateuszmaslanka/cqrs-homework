<?php declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Exception\InvalidArgumentException;

class ConfirmUserCreationWithSmsCommand
{
    /** @var int */
    private $phone;

    /** @var string */
    private $message;

    private function __construct(int $phone, string $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public static function createFromArray(array $data)
    {
        if (!array_key_exists('phone', $data)) {
            throw InvalidArgumentException::createForMissingParameter('phone');
        }

        return new self((int)$data['phone'], 'Your account has been created.');
    }

    public function phone(): int
    {
        return $this->phone;
    }

    public function message(): string
    {
        return $this->message;
    }
}
