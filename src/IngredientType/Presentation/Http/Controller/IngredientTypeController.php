<?php

namespace App\IngredientType\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/ingredient-types')]
final class IngredientTypeController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {}

    #[Route('/{id}', name: 'ingredient_type_get_instance', methods: ['GET'])]
    public function getInstance(Request $request):JsonResponse
    {
        $id = $request->attributes->getString('id');

        if ($id) {
            //$response = $this->queryBus->ask(null);

            return new JsonResponse(['id' => $id]);
        }

        return new JsonErrorResponse('Invalid request', 405);
    }
}
