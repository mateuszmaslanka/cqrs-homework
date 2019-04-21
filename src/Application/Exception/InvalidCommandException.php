<?php declare(strict_types=1);

namespace App\Application\Exception;

class InvalidCommandException extends ApplicationException
{
    public static function createForInvalidEmail(): self
    {
        return new self('Invalid email.');
    }
}
