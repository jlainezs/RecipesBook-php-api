<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Query\Ingredient\IngredientInstanceQuery;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GetIngredientController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredients/{id}', name: 'ingredient_get_instance', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');
        $query = new IngredientInstanceQuery($id);
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
