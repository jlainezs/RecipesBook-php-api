<?php
namespace App\IngredientType\Application\Query\IngredientType;

use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class FindIngredientTypeReferenceQueryHandler
{
    public function __construct(
       private IngredientTypeRepositoryInterface $repository
    ){}

    /**
     * @throws IngredientTypeNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(FindIngredientTypeReferenceQuery $query): IngredientTypeReference
    {
        $ingredientType = $this->repository->findOne(new AggregateRootId($query->ingredientTypeId));

        if (is_null($ingredientType))
        {
            throw new IngredientTypeNotFoundException();
        }

        return new IngredientTypeReference($ingredientType->getId()->toString());
    }
}
