<?php declare(strict_types=1);

namespace App\Domain\WriteModel;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Company
{
    /** @var UuidInterface */
    private $id;

    /** @var string */
    private $domain;

    /** @var int */
    private $userLimit;

    /** @var int */
    private $userCounter;

    private function __construct(UuidInterface $id, string $domain, int $userLimit, int $userCounter)
    {
        $this->id = $id;
        $this->domain = $domain;
        $this->userLimit = $userLimit;
        $this->userCounter = $userCounter;
    }

    public static function createNew(string $domain, int $userLimit): self
    {
        return new self(Uuid::uuid4(), $domain, $userLimit, 0);
    }

    public function domain()
    {
        return $this->domain;
    }
}
