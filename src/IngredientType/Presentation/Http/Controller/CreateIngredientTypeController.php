<?php

namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CreateIngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/ingredient-types/create', name: 'ingredient_types_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateIngredientTypeDto $dto):JsonResponse
    {
        $cmd = new CreateIngredientTypeCommand($dto->name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 201);
    }

}
