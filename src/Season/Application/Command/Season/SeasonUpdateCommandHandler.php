<?php
namespace App\Season\Application\Command\Season;

use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class SeasonUpdateCommandHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws SeasonNotFoundException
     * @throws SeasonEmptyNameException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(SeasonUpdateCommand $command): void
    {
        if ($season = $this->repository->findOne(new AggregateRootId($command->id)))
        {
            $season->rename($command->name);
            $this->repository->save($season);
        }
        else
        {
            throw new SeasonNotFoundException($command->id);
        }
    }
}
