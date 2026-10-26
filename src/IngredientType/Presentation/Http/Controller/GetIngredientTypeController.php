<?php
namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeDto;
use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeQueryFactory;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetIngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetIngredientTypeQueryFactory $queryFactory
    ){}

    #[Route('/api/v1/ingredient-types/{id}', name: 'get_ingredient_type', methods: ['GET'])]
    public function __invoke(#[MapRouteParameter]GetIngredientTypeDto $dto):JsonResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
