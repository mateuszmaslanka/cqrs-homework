<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Company;
use App\Domain\Exception\DomainAlreadyExistsException;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Exception\WriteException;
use App\Domain\Repository\CompanyReadInterface;
use App\Domain\Repository\CompanyWriteInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;

class CompanyRepository extends EntityRepository implements CompanyReadInterface, CompanyWriteInterface
{
    /**
     * @inheritDoc
     */
    public function add(Company $company): void
    {
        $em = $this->getEntityManager();

        $em->beginTransaction();
        try {
            $em->persist($company);
            $em->flush($company);
        } catch (UniqueConstraintViolationException $e) {
            $em->rollback();

            throw DomainAlreadyExistsException::createForEntity($company->domain(), Company::class);
        } catch (\Exception $e) {
            $em->rollback();

            throw new WriteException('Cannot save company to database.', 0, $e);
        }

        $em->commit();
    }

    /**
     * @inheritDoc
     */
    public function findByDomain(string $domain): Company
    {
        $criteria = [
            'domain' => $domain,
        ];

        /** @var Company $company */
        $company = $this->findOneBy($criteria);

        if (!$company) {
            throw EntityNotFoundException::createForEntity(Company::class, $criteria);
        }

        return $company;
    }

    /**
     * @inheritDoc
     */
    public function increaseUserCounter(Company $company): void
    {
        // TODO: Implement increaseUserCounter() method.
    }
}
