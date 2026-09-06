<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientType;

use App\IngredientType\Application\Query\IngredientType\IngredientTypeDto;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetIngredientTypeQueryHandler
{
    public function __construct(private IngredientTypeRepositoryInterface $repository)
    {}

    /**
     * @throws IngredientTypeNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(GetIngredientTypeQuery $query):GetIngredientTypeResponse
    {
        $ingredientType = $this->repository->findOne(new AggregateRootId($query->id));

        if ($ingredientType)
        {
            return new GetIngredientTypeResponse(new IngredientTypeDto(
                id: $ingredientType->getId()->toString(),
                name: $ingredientType->getName()->value(),
                createdAt: $ingredientType->getCreatedAt(),
                updatedAt: $ingredientType->getUpdatedAt(),
            ));
        }

        throw new IngredientTypeNotFoundException($query->id);
    }
}
