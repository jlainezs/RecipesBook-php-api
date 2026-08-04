<?php
namespace App\Season\Application\Query\Season;

use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SeasonInstanceQueryHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws SeasonNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(SeasonInstanceQuery $query):SeasonInstanceResponse
    {
        if ($season = $this->repository->findOne(new AggregateRootId($query->id)))
        {
            return new SeasonInstanceResponse(
                new SeasonDto(
                    id: $season->getId()->toString(),
                    name: $season->getName(),
                    createdAt: $season->getCreatedAt(),
                    updatedAt: $season->getUpdatedAt(),
                )
            );
        }

        throw new SeasonNotFoundException($query->id);
    }
}
