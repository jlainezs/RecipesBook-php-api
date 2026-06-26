<?php
namespace App\Season\Application\Command\Season;

use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SeasonDeleteHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws SeasonNotFoundException
     */
    public function __invoke(SeasonDeleteCommand $command): void
    {
        $season = $this->repository->findOne($command->id);
        if ($season)
        {
            $this->repository->delete($season);
        } else {
            throw new SeasonNotFoundException();
        }
    }
}
