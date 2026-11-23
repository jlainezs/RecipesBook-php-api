<?php
namespace App\Season\Application\Query\Season\GetSeason;

use App\Season\Application\Query\Season\SeasonDto;
use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetSeasonQueryHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws SeasonNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(GetSeasonQuery $query):GetSeasonQueryResponse
    {
        if ($season = $this->repository->findOne(new AggregateRootId($query->id)))
        {
            return new GetSeasonQueryResponse(SeasonDto::fromDomain($season));
        }

        throw new SeasonNotFoundException($query->id);
    }
}
