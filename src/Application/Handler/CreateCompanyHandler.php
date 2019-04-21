<?php declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\CreateCompanyCommand;
use App\Application\Exception\CommandHandlerException;
use App\Domain\Entity\Company;
use App\Domain\Exception\DomainAlreadyExistsException;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\CompanyReadInterface;
use App\Domain\Repository\CompanyWriteInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateCompanyHandler implements MessageHandlerInterface
{
    /** @var CompanyReadInterface */
    private $companies;

    public function __construct(CompanyWriteInterface $companies)
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
        } catch (DomainException $e) {
            throw new CommandHandlerException('Cannot add company.', 0, $e);
        }
    }
}
