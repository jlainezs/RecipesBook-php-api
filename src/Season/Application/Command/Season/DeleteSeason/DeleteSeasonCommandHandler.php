<?php
namespace App\Season\Application\Command\Season\DeleteSeason;

use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteSeasonCommandHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws SeasonNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(DeleteSeasonCommand $command): void
    {
        $season = $this->repository->findOne(new AggregateRootId($command->id));
        if ($season)
        {
            $this->repository->delete($season);
        } else {
            throw new SeasonNotFoundException();
        }
    }
}
