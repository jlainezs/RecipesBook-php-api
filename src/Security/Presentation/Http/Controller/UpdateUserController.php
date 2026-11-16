<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Command\User\UpdateUser\UpdateUserCommandFactory;
use App\Security\Application\Command\User\UpdateUser\UpdateUserDto;
use App\Security\Application\Command\User\UpdateUser\UpdateUserRouteDto;
use App\Security\Presentation\Http\Resource\UserResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateUserCommandFactory $commandFactory
    ){}

    #[Route('/api/v1/users/{id}', name: 'users_update', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['PATCH'])]
    public function __invoke(
        #[MapRouteParameter]
        UpdateUserRouteDto $userId,
        #[MapJsonApiPayload(UserResource::TYPE)]
        UpdateUserDto $dto): Response
    {
        $cmd = $this->commandFactory->createFromRequestDto($userId, $dto);
        $this->commandBus->dispatch($cmd);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
