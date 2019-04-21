<?php declare(strict_types=1);

namespace App\Application\Exception;

class InvalidArgumentException extends InvalidCommandException
{
    public static function createForMissingParameter(string $parameter): self
    {
        return new self(sprintf(
            'Missing parameter "%s".',
            $parameter
        ));
    }
}
