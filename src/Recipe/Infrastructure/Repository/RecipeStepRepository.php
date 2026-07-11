<?php
namespace App\Recipe\Infrastructure\Repository;

use App\Recipe\Domain\Model\RecipeStep;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Repository\RecipeStepRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecipeStepRepository extends ServiceEntityRepository implements RecipeStepRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeStep::class);
    }

    public function findSteps(string $recipeId): array
    {
        return parent::findBy(['recipeId' => $recipeId]);
    }

    public function save(Recipe $recipe, iterable $recipeSteps): void
    {
        if (empty($recipeSteps))
        {
            return;
        }

        foreach ($recipeSteps as $recipeStep)
        {
            $recipeStep->setRecipe($recipe);
            $this->getEntityManager()->persist($recipeStep);
        }

        $this->getEntityManager()->flush();
    }

    public function delete(iterable $recipeSteps): void
    {
        foreach ($recipeSteps as $recipeStep)
        {
            $this->getEntityManager()->remove($recipeStep);
        }

        $this->getEntityManager()->flush();
    }
}
