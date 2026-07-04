<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\IngredientDeleteCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteIngredientController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredients/{id}', name: 'ingredient_delete', methods: ['DELETE'])]
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->getString('id');
        $cmd = new IngredientDeleteCommand($id);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
