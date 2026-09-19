<?php
namespace App\Security\Presentation\Http\Controller;

use App\Security\Application\Query\User\GetUser\GetUserQuery;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetUserController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/users/{id}', name: 'users_get_instance', methods: ['GET'])]
    public function __invoke(string $id): JsonResponse
    {
        $query = new GetUserQuery($id);
        $this->validator->validate($query);
        $result = $this->queryBus->ask($query);

        return new JsonResponse($result);
    }
}
