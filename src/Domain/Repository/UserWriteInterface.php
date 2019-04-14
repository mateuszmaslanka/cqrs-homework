<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserWriteInterface
{
    const MAX_USERS_PER_COMPANY = 1;

    public function add(User $user);
}
