<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientCommand;
use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateIngredientController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredients/{id}', name: 'ingredient_update_instance', methods: ['PUT'])]
    public function __invoke(
        string $id,
        #[MapRequestPayload]
        UpdateIngredientDto $dto
    ): JsonResponse
    {
        $cmd = new UpdateIngredientCommand(
            $id, $dto->name, $dto->description, $dto->ingredientTypeId
        );
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
