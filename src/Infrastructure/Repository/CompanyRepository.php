<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Exception\DomainAlreadyExistsException;
use App\Domain\Repository\CompaniesInterface;
use App\Domain\WriteModel\Company;
use App\Infrastructure\Exception\RuntimeException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;

class CompanyRepository extends EntityRepository implements CompaniesInterface
{
    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function add(Company $company): void
    {
        $em = $this->getEntityManager();

        try {
            $em->persist($company);
            $em->flush($company);
        } catch (UniqueConstraintViolationException $e) {
            throw DomainAlreadyExistsException::createForEntity($company->domain(), Company::class);
        } catch (\Exception $e) {
            throw new RuntimeException('Cannot save company to database.', 0, $e);
        }
    }

    /**
     * @throws RuntimeException
     */
    public function increaseUserCounterWithDomain(string $domain): void
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $query = $connection->prepare('
                UPDATE
                    company
                SET
                    user_counter = user_counter + 1
                WHERE domain = :domain
            ');

            $query->execute([
                'domain' => $domain,
            ]);
        } catch (\Exception $e) {
            throw new RuntimeException('Cannot access database.', 0, $e);
        }
    }

    /**
     * @throws RuntimeException
     */
    public function canAddUserWithDomain(string $domain): bool
    {
        $connection = $this->getEntityManager()->getConnection();

        try {
            $canAdd = (bool)$connection->executeQuery('
                SELECT
                    count(1)
                FROM
                    company
                WHERE
                    domain = :domain
                    AND user_limit > user_counter
            ', [
                'domain' => $domain,
            ])->fetchColumn(0);
        } catch (\Exception $e) {
            throw new RuntimeException('Cannot access database.', 0, $e);
        }

        return $canAdd;
    }
}
