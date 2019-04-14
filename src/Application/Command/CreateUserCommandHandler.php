<?php declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Entity\User;
use App\Domain\Exception\UserException;
use App\Domain\Repository\UserWriteInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateUserCommandHandler implements MessageHandlerInterface
{
    private $userRepository;

    public function __construct(UserWriteInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws CommandHandlerException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $user = User::createUserWithCompany(
            $command->name(),
            $command->email(),
            $command->companyId()
        );

        try {
            $this->userRepository->add($user);
        } catch (UserException $e) {
            throw new CommandHandlerException($e->getMessage());
        }
    }
}
