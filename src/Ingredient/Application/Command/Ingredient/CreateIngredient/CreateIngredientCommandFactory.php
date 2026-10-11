<?php
namespace App\Ingredient\Application\Command\Ingredient\CreateIngredient;

use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;

readonly class CreateIngredientCommandFactory
{
    public function __construct(
        public readonly ApplicationDataValidator $validator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function createFromDto(CreateIngredientDto $dto): CreateIngredientCommand
    {
        $id = AggregateRootId::generateId();
        $cmd = new CreateIngredientCommand(
            $id->toString(),
            $dto->name,
            $dto->description,
            $dto->ingredientTypeId
        );
        $this->validator->validate($cmd);

        return $cmd;
    }
}
