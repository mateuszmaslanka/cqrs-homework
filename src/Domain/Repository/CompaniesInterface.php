<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Exception\DomainAlreadyExistsException;
use App\Domain\WriteModel\Company;

interface CompaniesInterface
{
    /**
     * @throws DomainAlreadyExistsException
     */
    public function add(Company $company): void;

    public function canAddUserWithDomain(string $domain): bool;

    public function increaseUserCounterWithDomain(string $domain): void;
}
