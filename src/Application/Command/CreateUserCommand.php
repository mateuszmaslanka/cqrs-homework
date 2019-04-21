<?php declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Exception\InvalidCommandException;
use App\Application\Exception\InvalidArgumentException;
use WMDE\EmailAddress\EmailAddress;

class CreateUserCommand
{
    /** @var string */
    private $name;

    /** @var EmailAddress */
    private $email;

    /** @var int */
    private $phone;

    private function __construct(string $name, EmailAddress $email, ?int $phone)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    /**
     * @throws InvalidCommandException
     */
    public static function createFromArray(array $data): self
    {
        if (!array_key_exists('name', $data)) {
            throw InvalidArgumentException::createForMissingParameter('name');
        }

        if (!array_key_exists('email', $data)) {
            throw InvalidArgumentException::createForMissingParameter('email');
        }

        $name = (string)$data['name'];

        try {
            $email = new EmailAddress((string)$data['email']);
        } catch (\InvalidArgumentException $e) {
            throw InvalidCommandException::createForInvalidEmail();
        }

        $phone = array_key_exists('phone', $data) ? (int)$data['phone'] : null;

        return new self($name, $email, $phone);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): EmailAddress
    {
        return $this->email;
    }

    public function phone(): ?int
    {
        return $this->phone;
    }
}
