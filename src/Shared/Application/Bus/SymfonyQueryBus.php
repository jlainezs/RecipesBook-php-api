<?php
namespace App\Shared\Application\Bus;

use App\Shared\Application\Bus\QueryBus;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class SymfonyQueryBus implements QueryBus
{
    public function __construct(private MessageBusInterface $queryBus) {}

    /**
     * @throws ExceptionInterface
     */
    public function ask(object $query): mixed
    {
        $envelope = $this->queryBus->dispatch($query);
        return $envelope->last(HandledStamp::class)?->getResult();
    }
}
