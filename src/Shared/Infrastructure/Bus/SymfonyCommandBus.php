<?php
namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\CommandBus;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly final class SymfonyCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandBus)
    {}

    /**
     * @throws ExceptionInterface
     */
    public function dispatch(object $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
