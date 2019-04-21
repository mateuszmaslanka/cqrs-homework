<?php declare(strict_types=1);

namespace App\Domain\WriteModel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use WMDE\EmailAddress\EmailAddress;

class User
{
    /** @var UuidInterface */
    private $id;

    /** @var string */
    private $name;

    /** @var EmailAddress */
    private $email;

    /** @var int */
    private $phone;

    private function __construct(UuidInterface $id, string $name, EmailAddress $email, ?int $phone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    public static function createNew(string $name, EmailAddress $email, ?int $phone): self
    {
        return new self(Uuid::uuid4(), $name, $email, $phone);
    }

    public function email(): EmailAddress
    {
        return $this->email;
    }
}
