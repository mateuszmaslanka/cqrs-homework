<?php declare(strict_types=1);

namespace App\Controller;

use App\Application\Command\CommandHandlerException;
use App\Application\Command\CreateUserCommand;
use App\Application\Command\InvalidCommandException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/user/create")
     */
    public function create(Request $request)
    {
        try {
            $createUserCommand = CreateUserCommand::createFromRequest($request);
        } catch (InvalidCommandException $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], 400);
        }

        try {
            $this->commandBus->dispatch($createUserCommand);
        } catch (CommandHandlerException $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], 400);
        }

        return $this->json([
            'ok' => 'User created',
        ]);
    }
}
