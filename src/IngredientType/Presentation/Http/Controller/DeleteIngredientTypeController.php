<?php
namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\DeleteIngredientType\DeleteIngredientTypeCommandFactory;
use App\IngredientType\Application\Command\IngredientType\DeleteIngredientType\DeleteIngredientTypeDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteIngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly DeleteIngredientTypeCommandFactory $commandFactory
    ){}

    #[Route('/api/v1/ingredient-types/{id}', name: 'ingredient_types_delete_instance', methods: ['DELETE'])]
    public function __invoke(#[MapRouteParameter] DeleteIngredientTypeDto $dto): JsonResponse
    {
        $cmd = $this->commandFactory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
