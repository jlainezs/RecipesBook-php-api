<?php

namespace App\Season\Application\Query\Season;

use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SeasonInstanceHandler
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    /**
     * @throws IngredientTypeNotFoundException
     */
    public function __invoke(SeasonInstanceQuery $query):SeasonInstanceResponse
    {
        $season = $this->repository->findOne($query->id);

        if ($season)
        {
            return new SeasonInstanceResponse(new SeasonDto(
                id: $season->getId()->toString(),
                name: $season->getName(),
                createdAt: $season->getCreatedAt(),
                updatedAt: $season->getUpdatedAt(),
            ));
        }

        throw new IngredientTypeNotFoundException($query->id);
    }
}
