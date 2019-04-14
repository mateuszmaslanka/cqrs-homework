<?php declare(strict_types=1);

namespace App\Repository;

use App\Domain\Entity\User;
use App\Domain\Exception\AddUserException;
use App\Domain\Exception\CompanyUserLimitExceededException;
use App\Domain\Exception\EmailAlreadyUsedException;
use App\Domain\Exception\UserException;
use App\Domain\Repository\UserWriteInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserWriteInterface
{
    /**
     * @throws UserException
     */
    public function add(User $user)
    {
        $em = $this->getEntityManager();

        $em->beginTransaction();
        try {
            $em->persist($user);
            $em->flush($user);
        } catch (UniqueConstraintViolationException $e) {
            $em->rollback();

            throw new EmailAlreadyUsedException('Email already used.');
        } catch (\Exception $e) {
            $em->rollback();

            throw new AddUserException('Unexpected problem with saving to database.');
        }

        if ($this->isCompanyUserLimitExceeded($user)) {
            $em->rollback();

            throw new CompanyUserLimitExceededException('Comapany user limit exceeded.');
        }

        $em->commit();
    }

    private function isCompanyUserLimitExceeded(User $user): bool
    {
        $connection = $this->getEntityManager()->getConnection();

        $query = $connection->prepare('SELECT COUNT(1) FROM user WHERE company_id = :companyId');
        $query->execute([
            'companyId' => $user->getCompanyId(),
        ]);

        $numberOfUsers = (int)$query->fetchColumn();

        return $numberOfUsers > self::MAX_USERS_PER_COMPANY;
    }
}
