<?php
namespace App\Season\Application\Command\Season;

use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SeasonDeleteCommandHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws SeasonNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(SeasonDeleteCommand $command): void
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
