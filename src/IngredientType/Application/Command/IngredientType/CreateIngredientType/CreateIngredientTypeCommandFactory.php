<?php
namespace App\IngredientType\Application\Command\IngredientType\CreateIngredientType;

use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;

readonly class CreateIngredientTypeCommandFactory
{
    public function __construct(
        public ApplicationDataValidator $validator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function createFromDto(CreateIngredientTypeDto $dto): CreateIngredientTypeCommand
    {
        $id = AggregateRootId::generateId();
        $cmd = new CreateIngredientTypeCommand(
            $id->toString(),
            $dto->name
        );
        $this->validator->validate($cmd);

        return $cmd;
    }
}
