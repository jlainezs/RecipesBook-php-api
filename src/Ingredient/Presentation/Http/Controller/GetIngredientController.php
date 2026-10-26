<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientDto;
use App\Ingredient\Application\Query\Ingredient\GetIngredient\GetIngredientQueryFactory;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetIngredientController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetIngredientQueryFactory $queryFactory
    ){}

    #[Route('/api/v1/ingredients/{id}', name: 'ingredient_get_instance', methods: ['GET'])]
    public function __invoke(#[MapRouteParameter] GetIngredientDto $dto): JsonResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
