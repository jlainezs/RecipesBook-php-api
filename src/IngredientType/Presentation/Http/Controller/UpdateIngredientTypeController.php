<?php
namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateIngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredient-types/{id}', name: 'ingredient_types_update_instance', methods: ['PUT'])]
    public function __invoke(string $id, #[MapRequestPayload] UpdateIngredientTypeDto $dto): JsonResponse
    {
        $cmd = new UpdateIngredientTypeCommand($id, $dto->name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
