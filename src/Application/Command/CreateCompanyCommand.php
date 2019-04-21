<?php declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Exception\InvalidCommandException;
use App\Application\Exception\InvalidArgumentException;

class CreateCompanyCommand
{
    /** @var string */
    private $domain;

    /** @var int */
    private $userLimit;

    private function __construct(string $domain, int $userLimit)
    {
        $this->domain = $domain;
        $this->userLimit = $userLimit;
    }

    /**
     * @throws InvalidCommandException
     */
    public static function createFromArray(array $data)
    {
        if (!array_key_exists('domain', $data)) {
            throw InvalidArgumentException::createForMissingParameter('domain');
        }

        if (!array_key_exists('userLimit', $data)) {
            throw InvalidArgumentException::createForMissingParameter('userLimit');
        }

        $domain = (string)$data['domain'];
        $userLimit = (int)['userLimit'];

        return new self($domain, $userLimit);
    }

    public function domain(): string
    {
        return $this->domain;
    }

    public function userLimit(): int
    {
        return $this->userLimit;
    }
}
