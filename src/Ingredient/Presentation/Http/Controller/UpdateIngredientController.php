<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientCommandFactory;
use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientDto;
use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientRouteDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateIngredientController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateIngredientCommandFactory $cmdFactory
    ){}

    #[Route('/api/v1/ingredients/{id}', name: 'ingredient_update_instance', methods: ['PUT'])]
    public function __invoke(
        #[MapRouteParameter]
        UpdateIngredientRouteDto $routeDto,
        #[MapRequestPayload]
        UpdateIngredientDto $dto
    ): JsonResponse
    {
        $cmd = $this->cmdFactory->createFromRequest($routeDto, $dto);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
