<?php
namespace App\Season\Application\Command\Season\UpdateSeason;

use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class UpdateSeasonCommandHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws SeasonNotFoundException
     * @throws SeasonEmptyNameException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(UpdateSeasonCommand $command): void
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
