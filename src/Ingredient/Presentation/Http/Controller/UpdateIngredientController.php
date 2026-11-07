<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientCommandFactory;
use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientDto;
use App\Ingredient\Application\Command\Ingredient\UpdateIngredient\UpdateIngredientRouteDto;
use App\Ingredient\Presentation\Http\Resoource\IngredientResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateIngredientController extends AbstractController
{
    function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateIngredientCommandFactory $cmdFactory
    ){}

    #[Route('/api/v1/ingredients/{id}', name: 'ingredient_update_instance', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['PATCH'])]
    public function __invoke(
        #[MapRouteParameter]
        UpdateIngredientRouteDto $routeDto,
        #[MapJsonApiPayload(IngredientResource::TYPE)]
        UpdateIngredientDto $dto
    ): Response
    {
        $cmd = $this->cmdFactory->createFromRequest($routeDto, $dto);
        $this->commandBus->dispatch($cmd);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
