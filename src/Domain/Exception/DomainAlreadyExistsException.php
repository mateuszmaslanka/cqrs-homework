<?php declare(strict_types=1);

namespace App\Domain\Exception;

class DomainAlreadyExistsException extends WriteException
{
    public static function createForEntity(string $domain, string $entity): self
    {
        return new self(sprintf(
            'Duplicated domain "%s" in entity "%s".',
            $domain,
            $entity
        ));
    }
}
