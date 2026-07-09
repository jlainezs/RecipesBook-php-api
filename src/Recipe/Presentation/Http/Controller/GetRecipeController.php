<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Query\Recipe\RecipeInstanceQuery;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GetRecipeController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/recipes/{id}', name: 'get_recipe', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');
        $query = new RecipeInstanceQuery($id);
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
