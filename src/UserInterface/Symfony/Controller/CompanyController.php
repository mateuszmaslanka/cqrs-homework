<?php declare(strict_types=1);

namespace App\UserInterface\Symfony\Controller;

use App\Application\Command\CreateCompanyCommand;
use App\Application\Exception\CommandHandlerException;
use App\Application\Exception\InvalidCommandException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/company/create")
     */
    public function create(Request $request)
    {
        if ('json' !== $request->getContentType()) {
            return $this->json([], Response::HTTP_BAD_REQUEST);
        }

        $requestData = json_decode($request->getContent(), true);

        try {
            $createCompanyCommand = CreateCompanyCommand::createFromArray($requestData);
        } catch (InvalidCommandException $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->commandBus->dispatch($createCompanyCommand);
        } catch (CommandHandlerException $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'msg' => 'Company created.',
        ]);
    }
}
