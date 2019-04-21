<?php declare(strict_types=1);

namespace App\Domain\Exception;

class EntityNotFoundException extends DomainException
{
    public static function createForEntity(string $entity, array $criteria): self
    {
        $criteriaString = '';

        foreach ($criteria as $name => $value) {
            $criteriaString .= sprintf(
                '%s = "%s", ',
                $name,
                $value
            );
        }
        rtrim($criteriaString, ', ');

        return new self(sprintf(
            'Cannot found "%s" with criteria: %s.',
            $entity,
            $criteriaString
        ));
    }
}
