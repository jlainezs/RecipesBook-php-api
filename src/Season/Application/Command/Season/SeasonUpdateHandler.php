<?php
namespace App\Season\Application\Command\Season;

use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class SeasonUpdateHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws SeasonNotFoundException
     * @throws SeasonEmptyNameException
     */
    public function __invoke(SeasonUpdateCommand $command): void
    {
        if ($season = $this->repository->findOne($command->id))
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
