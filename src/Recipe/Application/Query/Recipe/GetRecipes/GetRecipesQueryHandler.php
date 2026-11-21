<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipes;

use App\Recipe\Application\Query\Recipe\RecipeDto;
use App\Recipe\Application\Service\RecipeItemsPager;
use App\Recipe\Domain\Model\Recipe;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetRecipesQueryHandler
{
    public function __construct(private RecipeItemsPager $list)
    {}

    public function __invoke(GetRecipesQuery $query): GetRecipesQueryResponse
    {
        $itemsDto = array_map(
            fn(Recipe $recipe) => RecipeDto::fromDomain($recipe),
            $this->list->items($query->offset, $query->limit)
        );

        return new GetRecipesQueryResponse($itemsDto);
    }
}
