<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use App\Domain\Exception\EmailAlreadyExistsException;
use App\Domain\Exception\WriteException;

interface UserWriteInterface
{
    /**
     * @throws EmailAlreadyExistsException
     * @throws WriteException
     */
    public function add(User $user): void;
}
