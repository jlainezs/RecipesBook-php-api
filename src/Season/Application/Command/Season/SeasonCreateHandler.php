<?php
namespace App\Season\Application\Command\Season;

use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Season\Domain\Model\Season;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SeasonCreateHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws SeasonEmptyNameException
     */
    public function __invoke(SeasonCreateCommand $command): void
    {
        $season = Season::create($command->name);
        $this->repository->save($season);
    }
}
