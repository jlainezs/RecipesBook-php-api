<?php

namespace App\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeDto;
use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeQuery;
use App\IngredientType\Application\Query\IngredientType\GetIngredientTypes\GetIngredientTypesQueryDto;
use App\IngredientType\Domain\Model\IngredientType;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class CreateIngredientTypeController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/ingredient-types/create', name: 'create_ingredient_type', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateIngredientTypeDto $dto):JsonResponse
    {
        $id = AggregateRootId::generateId();
        $cmd = new CreateIngredientTypeCommand($id->toString(), $dto->name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        $query = new GetIngredientTypeQuery($id->toString());
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response, 201);
    }
}
