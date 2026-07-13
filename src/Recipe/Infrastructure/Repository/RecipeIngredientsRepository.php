<?php
namespace App\Recipe\Infrastructure\Repository;

use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Model\RecipeIngredient;
use App\Recipe\Domain\Repository\RecipeIngredientRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RecipeIngredientsRepository extends ServiceEntityRepository implements RecipeIngredientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeIngredient::class);
    }

    public function findIngredients(string $recipeId): array
    {
        return parent::findBy(['recipeId' => $recipeId])
            ->orderBy('ordering', 'ASC');
    }

    public function save(Recipe $recipe, iterable $recipeIngredients): void
    {
        if (empty($recipeIngredients)){
            return;
        }

        foreach ($recipeIngredients as $recipeIngredient){
            $recipeIngredient->setRecipe($recipe);
            $this->getEntityManager()->persist($recipeIngredient);
        }

        $this->getEntityManager()->flush();
    }

    public function delete(iterable $recipeIngredients): void
    {
        foreach ($recipeIngredients as $recipeIngredient)
        {
            $this->getEntityManager()->remove($recipeIngredient);
        }

        $this->getEntityManager()->flush();
    }
}
