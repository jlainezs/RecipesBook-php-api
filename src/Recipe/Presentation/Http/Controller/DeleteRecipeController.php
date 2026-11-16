<?php
namespace App\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\RecipeDelete\DeleteRecipeCommandFactory;
use App\Recipe\Application\Command\RecipeDelete\DeleteRecipeDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteRecipeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly DeleteRecipeCommandFactory $cmdFactory
    ){}

    #[Route('/api/v1/recipes/{id}', name: 'delete_recipe', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['DELETE'])]
    public function __invoke(#[MapRouteParameter] DeleteRecipeDto $request): Response
    {
        $cmd = $this->cmdFactory->createFromDto($request);
        $this->commandBus->dispatch($cmd);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
