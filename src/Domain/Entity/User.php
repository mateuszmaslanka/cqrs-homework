<?php declare(strict_types=1);

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class User
{
    private $id;
    private $name;
    private $email;
    private $companyId;

    private function __construct(Uuid $id, string $name, string $email, ?UuidInterface $companyId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->companyId = $companyId;
    }

    public static function createUserWithCompany(string $name, string $email, UuidInterface $companyId): self
    {
        return new self(Uuid::uuid4(), $name, $email, $companyId);
    }

    public function getCompanyId(): UuidInterface
    {
        return $this->companyId;
    }
}
