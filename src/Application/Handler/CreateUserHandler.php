<?php declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CreateUserCommand;
use App\Application\Exception\CommandHandlerException;
use App\Domain\Entity\User;
use App\Domain\Exception\DomainException;
use App\Domain\Exception\EmailAlreadyExistsException;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Repository\CompanyReadInterface;
use App\Domain\Repository\CompanyWriteInterface;
use App\Domain\Repository\UserWriteInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateUserHandler implements MessageHandlerInterface
{
    /** @var UserWriteInterface */
    private $users;

    /** @var CompanyReadInterface */
    private $companies;

    /** @var CompanyWriteInterface */
    private $companyRepository;

    public function __construct(UserWriteInterface $users, CompanyReadInterface $companies, CompanyWriteInterface $companyRepository)
    {
        $this->users = $users;
        $this->companies = $companies;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @throws CommandHandlerException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        try {
            $company = $this->companies->findByDomain($command->email()->getDomain());
        } catch (EntityNotFoundException $e) {
            throw new CommandHandlerException('Company does not exist.', 0, $e);
        }

        if ($company->isUserLimitReached()) {
            throw new CommandHandlerException('User limit reached.');
        }

        $user = User::createNew($command->name(), $command->email(), $command->phone());

        try {
            // TODO: transaction (?)
            $this->users->add($user);
            $this->companyRepository->increaseUserCounter($company);
        } catch (EmailAlreadyExistsException $e) {
            throw new CommandHandlerException('User already exists.', 0, $e);
        } catch (DomainException $e) {
            throw new CommandHandlerException('Cannot add user.', 0, $e);
        }
    }
}
