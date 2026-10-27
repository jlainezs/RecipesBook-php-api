<?php
namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQuery;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesDto;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryFactory;
use App\IngredientType\Presentation\Http\Response\IngredientTypesListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class GetIngredientTypesController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetIngredientTypesQueryFactory $queryFactory
    ){}

    #[Route('/ingredient-types', name: 'ingredient_types_list', methods: ['GET'])]
    public function __invoke(#[MapQueryString] GetIngredientTypesDto $queryDto): JsonResponse
    {
        $query = $this->queryFactory->createFromDto($queryDto);

        $response = $this->queryBus->ask($query);
        return IngredientTypesListJsonResponse::create($response->items);
    }
}
