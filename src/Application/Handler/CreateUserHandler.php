<?php declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CreateUserCommand;
use App\Application\Exception\CommandHandlerException;
use App\Domain\Exception\EmailAlreadyExistsException;
use App\Domain\Repository\CompaniesInterface;
use App\Domain\Repository\UsersInterface;
use App\Domain\WriteModel\User;
use App\Infrastructure\Exception\InfrastructureException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateUserHandler implements MessageHandlerInterface
{
    /** @var UsersInterface */
    private $users;

    /** @var CompaniesInterface */
    private $companies;

    public function __construct(UsersInterface $users, CompaniesInterface $companies)
    {
        $this->users = $users;
        $this->companies = $companies;
    }

    /**
     * @throws CommandHandlerException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $domain = $command->email()->getDomain();

        try {
            if (!$this->companies->canAddUserWithDomain($domain)) {
                throw new CommandHandlerException('Cannot create user with this domain.');
            };
        } catch (InfrastructureException $e) {
            throw new CommandHandlerException('Cannot create user.', 0, $e);
        }

        $user = User::createNew($command->name(), $command->email(), $command->phone());

        try {
            $this->users->add($user);
            $this->companies->increaseUserCounterWithDomain($domain);
        } catch (EmailAlreadyExistsException $e) {
            throw new CommandHandlerException('User already exists.', 0, $e);
        } catch (InfrastructureException $e) {
            throw new CommandHandlerException('Cannot create user.', 0, $e);
        }
    }
}
