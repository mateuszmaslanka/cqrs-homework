<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Company;
use App\Domain\Exception\EntityNotFoundException;

interface CompanyReadInterface
{
    /**
     * @throws EntityNotFoundException
     */
    public function findByDomain(string $domain): Company;
}
