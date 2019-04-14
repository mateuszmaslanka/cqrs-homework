<?php declare(strict_types=1);

namespace App\Application\Command;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateUserCommand
{
    private $name;
    private $email;
    private $companyId;

    private function __construct(string $name, string $email, UuidInterface $companyId)
    {
        $this->name = $name;
        $this->email = $email;
        $this->companyId = $companyId;
    }

    /**
     * @throws InvalidCommandException
     */
    public static function createFromRequest(Request $request): self
    {
        if ('json' !== $request->getContentType()) {
            throw new InvalidCommandException('Invalid request');
        }

        $data = json_decode($request->getContent(), true);

        $name = (string)($data['name'] ?? '');
        $email = (string)($data['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidCommandException('Invalid user email');
        }

        try {
            $companyId = Uuid::fromString($data['companyId'] ?? '');
        } catch (InvalidUuidStringException $e) {
            throw new InvalidCommandException('Invalid company id');
        }

        return new self($name, $email, $companyId);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function companyId(): UuidInterface
    {
        return $this->companyId;
    }
}
