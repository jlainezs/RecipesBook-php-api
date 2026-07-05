<?php
namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceQuery;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GetIngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredient-types/{id}', name: 'ingredient_types_get_instance', methods: ['GET'])]
    public function __invoke(Request $request):JsonResponse
    {
        $id = $request->attributes->getString('id');
        $query = new IngredientTypeInstanceQuery($id);
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
