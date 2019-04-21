<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Company;
use App\Domain\Exception\DomainAlreadyExistsException;
use App\Domain\Exception\WriteException;

interface CompanyWriteInterface
{
    /**
     * @throws DomainAlreadyExistsException
     * @throws WriteException
     */
    public function add(Company $company): void;

    /**
     * @throws WriteException
     */
    public function increaseUserCounter(Company $company): void;
}
