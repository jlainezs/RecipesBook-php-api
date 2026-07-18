<?php
namespace App\IngredientType\Application\Query\IngredientType;

use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class FindIngredientTypeReferenceQueryHandler
{
    public function __construct(
       private IngredientTypeRepositoryInterface $repository
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(FindIngredientTypeReferenceQuery $query)
    {
        $ingredientType = $this->repository->findOne($query->ingredientTypeId);

        return new IngredientTypeReference($ingredientType->getId()->toString());
    }
}
