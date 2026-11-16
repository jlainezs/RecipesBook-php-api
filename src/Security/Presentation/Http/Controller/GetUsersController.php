<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Query\User\GetUsers\GetUsersDto;
use App\Security\Application\Query\User\GetUsers\GetUsersQueryFactory;
use App\Security\Presentation\Http\Resource\UserResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class GetUsersController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetUsersQueryFactory $queryFactory,
        private readonly UserResource $resource
    ){}

    #[Route('/users', 'users_list', defaults: [JsonApi::ROUTE_FLAG => true], methods:['GET'])]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        GetUsersDto $dto
    ): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse(
            $this->resource->collection($response->items, $query->offset, $query->limit)
        );
    }
}
