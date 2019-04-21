<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Exception\EmailAlreadyExistsException;
use App\Domain\WriteModel\User;

interface UsersInterface
{
    /**
     * @throws EmailAlreadyExistsException
     */
    public function add(User $user): void;
}
