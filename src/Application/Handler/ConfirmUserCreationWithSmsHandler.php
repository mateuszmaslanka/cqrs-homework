<?php declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Command\ConfirmUserCreationWithSmsCommand;
use App\Application\Exception\CommandHandlerException;
use App\Application\Service\SmsSender\SmsSenderException;
use App\Application\Service\SmsSender\SmsSenderInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ConfirmUserCreationWithSmsHandler implements MessageHandlerInterface
{
    /** @var SmsSenderInterface */
    private $smsSender;

    public function __construct(SmsSenderInterface $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    /**
     * @throws CommandHandlerException
     */
    public function __invoke(ConfirmUserCreationWithSmsCommand $command): void
    {
        try {
            $this->smsSender->send($command->phone(), $command->message());
        } catch (SmsSenderException $e) {
            throw new CommandHandlerException('Cannot send SMS confirmation.', 0, $e);
        }
    }
}
