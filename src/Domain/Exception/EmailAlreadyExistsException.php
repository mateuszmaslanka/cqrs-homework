<?php declare(strict_types=1);

namespace App\Domain\Exception;

use WMDE\EmailAddress\EmailAddress;

class EmailAlreadyExistsException extends WriteException
{
    public static function createForEntity(EmailAddress $email, string $entity): self
    {
        return new self(sprintf(
            'Duplicated email "%s" in entity "%s".',
            $email->getFullAddress(),
            $entity
        ));
    }
}
