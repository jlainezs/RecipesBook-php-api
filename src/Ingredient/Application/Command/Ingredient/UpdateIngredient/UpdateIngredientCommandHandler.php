<?php
namespace App\Ingredient\Application\Command\Ingredient\UpdateIngredient;

use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\IngredientType\Application\Query\IngredientTypeReference\FindIngredientTypeReference\FindIngredientTypeReferenceQuery;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

#[AsMessageHandler]
readonly final class UpdateIngredientCommandHandler
{
    public function __construct(
        private IngredientRepositoryInterface $ingredientRepository,
        private QueryBus $queryBus
    ){}


    /**
     * @throws IngredientTypeNotFoundException|ExceptionInterface
     */
    private function findIngredientTypeReference(string $ingredientTypeId): IngredientTypeReference
    {
        $envelope = $this->queryBus->ask(
            new FindIngredientTypeReferenceQuery($ingredientTypeId)
        );

        if ($envelope === null)
        {
            throw new IngredientTypeNotFoundException($ingredientTypeId);
        }

        return $envelope;
    }


    /**
     * @throws IngredientTypeNotFoundException
     * @throws IngredientNotFoundException
     * @throws EmptyIdNotAllowedException
     * @throws ExceptionInterface
     */
    public function __invoke(UpdateIngredientCommand $command): void
    {
        $ingredient = $this->ingredientRepository->findOne(new AggregateRootId($command->id));

        if (!$ingredient) {
            throw new IngredientNotFoundException($command->id);
        }

        $ingredientTypeReference = null;

        if ($command->ingredientTypeId !== null)
        {
            $ingredientTypeReference = $this->findIngredientTypeReference($command->ingredientTypeId);
        }

        if (null === $ingredientTypeReference)
        {
            throw new IngredientTypeNotFoundException($command->ingredientTypeId);
        }

        if ($ingredientTypeReference)
        {
            $ingredient->rename($command->name);
            $ingredient->changeDescription($command->description);
            $ingredient->changeIngredientType(new IngredientTypeReference($command->ingredientTypeId));
            $this->ingredientRepository->save($ingredient);
        }
        else
        {
            throw new IngredientTypeNotFoundException($command->ingredientTypeId);
        }
    }
}
