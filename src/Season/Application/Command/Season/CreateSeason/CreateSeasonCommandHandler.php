<?php
namespace App\Season\Application\Command\Season\CreateSeason;

use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Season\Domain\Model\Season;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateSeasonCommandHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws SeasonEmptyNameException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(CreateSeasonCommand $command): void
    {
        $season = Season::create($command->name);
        $this->repository->save($season);
    }
}
