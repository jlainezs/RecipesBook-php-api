<?php

namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Query\IngredientType\IngredientTypeInstanceQuery;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\Response\JsonErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
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

        try
        {
            $response = $this->queryBus->ask(new IngredientTypeInstanceQuery($id));
            return new JsonResponse($response);
        }
        catch (HandlerFailedException $t)
        {
            if ($t->getPrevious() instanceof IngredientTypeNotFoundException)
            {
                return new JsonErrorResponse(
                    $t->getPrevious()->getMessage(),
                    404
                );
            }

            return new JsonErrorResponse($t->getMessage(), 500);
        }
    }
}
