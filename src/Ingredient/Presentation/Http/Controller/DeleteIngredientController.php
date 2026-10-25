<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\DeleteIngredient\DeleteIngredientCommand;
use App\Ingredient\Application\Command\Ingredient\DeleteIngredient\DeleteIngredientCommandFactory;
use App\Ingredient\Application\Command\Ingredient\DeleteIngredient\DeleteIngredientDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteIngredientController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly DeleteIngredientCommandFactory $factory,
    ){}

    #[Route('/api/v1/ingredients/{id}', name: 'ingredient_delete', methods: ['DELETE'])]
    public function __invoke(#[MapRouteParameter] DeleteIngredientDto $dto): JsonResponse
    {
        $cmd = $this->factory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
