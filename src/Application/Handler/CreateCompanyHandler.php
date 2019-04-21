<?php declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CreateCompanyCommand;
use App\Application\Exception\CommandHandlerException;
use App\Domain\Exception\DomainAlreadyExistsException;
use App\Domain\Repository\CompaniesInterface;
use App\Domain\WriteModel\Company;
use App\Infrastructure\Exception\InfrastructureException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateCompanyHandler implements MessageHandlerInterface
{
    /** @var CompaniesInterface */
    private $companies;

    public function __construct(CompaniesInterface $companies)
    {
        $this->companies = $companies;
    }

    /**
     * @throws CommandHandlerException
     */
    public function __invoke(CreateCompanyCommand $command): void
    {
        $company = Company::createNew($command->domain(), $command->userLimit());

        try {
            $this->companies->add($company);
        } catch (DomainAlreadyExistsException $e) {
            throw new CommandHandlerException('Company already exists.', 0, $e);
        } catch (InfrastructureException $e) {
            throw new CommandHandlerException('Cannot add company.', 0, $e);
        }
    }
}
