<?php
namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeCommandFactory;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeDto;
use App\IngredientType\Application\Command\IngredientType\UpdateIngredientType\UpdateIngredientTypeRouteDto;
use App\IngredientType\Presentation\Http\Resource\IngredientTypeResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateIngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateIngredientTypeCommandFactory $cmdFactory
    ){}

    #[Route('/api/v1/ingredient-types/{id}', name: 'ingredient_types_update_instance', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['PATCH'])]
    public function __invoke(
        #[MapRouteParameter]
        UpdateIngredientTypeRouteDto $routeDto,
        #[MapJsonApiPayload(IngredientTypeResource::TYPE)]
        UpdateIngredientTypeDto $bodyDto)
    : Response
    {
        $cmd = $this->cmdFactory->createFromRequest($routeDto, $bodyDto);
        $this->commandBus->dispatch($cmd);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
