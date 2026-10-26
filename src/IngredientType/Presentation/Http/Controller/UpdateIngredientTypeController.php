<?php
namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeCommandFactory;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeDto;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeRouteDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateIngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateIngredientTypeCommandFactory $cmdFactory
    ){}

    #[Route('/api/v1/ingredient-types/{id}', name: 'ingredient_types_update_instance', methods: ['PUT'])]
    public function __invoke(
        #[MapRouteParameter] UpdateIngredientTypeRouteDto $routeDto,
        #[MapRequestPayload] UpdateIngredientTypeDto      $bodyDto): JsonResponse
    {
        $cmd = $this->cmdFactory->createFromRequest($routeDto, $bodyDto);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
