<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Query\User\GetUsers\GetUsersDto;
use App\Security\Application\Query\User\GetUsers\GetUsersQuery;
use App\Security\Presentation\Http\Response\UsersJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class GetUsersController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/users', 'users_list', ['GET'])]
    public function __invoke(#[MapQueryString] GetUsersDto $dto): JsonResponse
    {
        $query = new GetUsersQuery(
            offset: $dto->offset,
            limit: $dto->limit,
        );
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return UsersJsonResponse::create($response->items);
    }
}
