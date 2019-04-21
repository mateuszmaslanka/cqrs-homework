<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Exception\EmailAlreadyExistsException;
use App\Domain\Exception\WriteException;
use App\Domain\Repository\UserWriteInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserWriteInterface
{
    /**
     * @inheritDoc
     */
    public function add(User $user): void
    {
        $em = $this->getEntityManager();

        $em->beginTransaction();
        try {
            $em->persist($user);
            $em->flush($user);
        } catch (UniqueConstraintViolationException $e) {
            $em->rollback();

            throw EmailAlreadyExistsException::createForEntity($user->email(), User::class);
        } catch (\Exception $e) {
            $em->rollback();

            throw new WriteException('Cannot save user to database.', 0, $e);
        }

        $em->commit();
    }
}
