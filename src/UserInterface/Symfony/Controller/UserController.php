<?php declare(strict_types=1);

namespace App\UserInterface\Symfony\Controller;

use App\Application\Command\ConfirmUserCreationWithSmsCommand;
use App\Application\Command\CreateUserCommand;
use App\Application\Exception\CommandHandlerException;
use App\Application\Exception\InvalidCommandException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        if ('json' !== $request->getContentType()) {
            return $this->json([], Response::HTTP_BAD_REQUEST);
        }

        $requestData = json_decode($request->getContent(), true);

        try {
            $createUserCommand = CreateUserCommand::createFromArray($requestData);
        } catch (InvalidCommandException $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->commandBus->dispatch($createUserCommand);
        } catch (CommandHandlerException $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $sendSmsCommand = ConfirmUserCreationWithSmsCommand::createFromArray($requestData);
            $this->commandBus->dispatch($createUserCommand);
        } catch (InvalidCommandException $e) {
        } catch (CommandHandlerException $e) {
            // TODO: log error
        }

        return $this->json([
            'msg' => 'User created.',
        ]);
    }
}
