<?php
namespace App\Recipe\Application\Query\Recipe;

use App\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Recipe\Infrastructure\Repository\RecipeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecipeInstanceQueryHandler
{
    public function __construct(private RecipeRepository $repository)
    {}

    /**
     * @throws RecipeNotFoundException
     */
    public function __invoke(RecipeInstanceQuery $query): RecipeInstanceResponse
    {
        if ($recipe = $this->repository->findOne($query->id))
        {
            $mapped_steps = [];

            foreach ($recipe->getSteps() as $step)
            {
                $mapped_steps[] = new RecipeStepDto(
                    id: $step->getId()->toString(),
                    description: $step->getDescription(),
                    ordering: $step->getOrdering(),
                    createdAt: $step->getCreatedAt(),
                    updatedAt: $step->getUpdatedAt(),
                );
            }

            return new RecipeInstanceResponse(new RecipeDto(
                id: $recipe->getId()->toString(),
                name: $recipe->getName(),
                servings: $recipe->getServings(),
                rating: $recipe->getRating(),
                description: $recipe->getDescription(),
                source: $recipe->getSource(),
                steps: $mapped_steps,
                createdAt: $recipe->getCreatedAt(),
                updatedAt: $recipe->getUpdatedAt()
            ));
        }

        throw new RecipeNotFoundException($query->id);
    }
}
