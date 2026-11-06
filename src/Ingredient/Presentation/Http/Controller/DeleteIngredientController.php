<?php
namespace App\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Application\Command\Ingredient\DeleteIngredient\DeleteIngredientCommandFactory;
use App\Ingredient\Application\Command\Ingredient\DeleteIngredient\DeleteIngredientRouteDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteIngredientController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly DeleteIngredientCommandFactory $factory,
    ){}

    #[Route('/api/v1/ingredients/{id}', name: 'ingredient_delete', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['DELETE'])]
    public function __invoke(#[MapRouteParameter] DeleteIngredientRouteDto $dto): Response
    {
        $cmd = $this->factory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);

        return new Response(status:Response::HTTP_NO_CONTENT);
    }
}
