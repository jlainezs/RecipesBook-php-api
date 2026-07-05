<?php
namespace App\IngredientType\Application\Query\IngredientType;

use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientTypeInstanceQueryHandler
{
    public function __construct(private IngredientTypeRepositoryInterface $repository)
    {}

    /**
     * @throws IngredientTypeNotFoundException
     */
    public function __invoke(IngredientTypeInstanceQuery $query):IngredientTypeInstanceResponse
    {
        $ingredientType = $this->repository->findOne($query->id);

        if ($ingredientType)
        {
            return new IngredientTypeInstanceResponse(new IngredientTypeDto(
                id: $ingredientType->getId()->toString(),
                name: $ingredientType->getName(),
                createdAt: $ingredientType->getCreatedAt(),
                updatedAt: $ingredientType->getUpdatedAt(),
            ));
        }

        throw new IngredientTypeNotFoundException($query->id);
    }
}
