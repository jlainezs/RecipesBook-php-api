<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\GetRecipe\GetRecipeDto;
use App\Recipe\Application\Query\Recipe\GetRecipe\GetRecipeQueryFactory;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetRecipeController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetRecipeQueryFactory $queryFactory
    ){}

    #[Route('/api/v1/recipes/{id}', name: 'get_recipe', methods: ['GET'])]
    public function __invoke(#[MapRouteParameter] GetRecipeDto $request): JsonResponse
    {
        $query = $this->queryFactory->createFromDto($request);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
