<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Query\User\GetUser\GetUserQueryFactory;
use App\Security\Application\Query\User\GetUser\GetUserRouteDto;
use App\Security\Presentation\Http\Resource\UserResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class GetUserController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetUserQueryFactory $queryFactory,
        private readonly UserResource $resource
    ){}

    #[Route('/api/v1/users/{id}', name: 'users_get_instance', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(#[MapRouteParameter] GetUserRouteDto $dto): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $result = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->item($result->user));
    }
}
