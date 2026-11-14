<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Command\User\CreateUser\CreateUserCommandFactory;
use App\Security\Application\Command\User\CreateUser\CreateUserDto;
use App\Security\Presentation\Http\Resource\UserResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateUserCommandFactory $commandFactory,
        private readonly UserResource $resource
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/users', name: 'users_create', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['POST'])]
    public function __invoke(
        #[MapJsonApiPayload(UserResource::TYPE)]
        CreateUserDto $createUserDto
    ): JsonApiResponse
    {
        $cmd = $this->commandFactory->createFromDto($createUserDto);
        $this->commandBus->dispatch($cmd);

        return new JsonApiResponse(
            document: $this->resource->itemReference($cmd->id),
            status:Response::HTTP_CREATED,
            headers: [
                'Location' => $this->resource->selfUrl($cmd->id),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
