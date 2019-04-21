<?php declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Exception\EmailAlreadyExistsException;
use App\Domain\Repository\UsersInterface;
use App\Domain\WriteModel\User;
use App\Infrastructure\Exception\RuntimeException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UsersInterface
{
    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function add(User $user): void
    {
        $em = $this->getEntityManager();

        try {
            $em->persist($user);
            $em->flush($user);
        } catch (UniqueConstraintViolationException $e) {
            throw EmailAlreadyExistsException::createForEntity($user->email(), User::class);
        } catch (\Exception $e) {
            throw new RuntimeException('Cannot save user to database.', 0, $e);
        }
    }
}
