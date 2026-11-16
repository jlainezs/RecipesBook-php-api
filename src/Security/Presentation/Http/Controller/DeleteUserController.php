<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Command\User\DeleteUser\DeleteUserCommandFactory;
use App\Security\Application\Command\User\DeleteUser\DeleteUserDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBus               $commandBus,
        private readonly DeleteUserCommandFactory $commandFactory,
    ){}

    #[Route('/api/v1/users/{id}', name: 'users_delete_instance', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['DELETE'])]
    public function __invoke(#[MapRouteParameter] DeleteUserDto $request): Response
    {
        $cmd = $this->commandFactory->createFromDto($request);
        $this->commandBus->dispatch($cmd);

        return new Response(null, 204);
    }
}
