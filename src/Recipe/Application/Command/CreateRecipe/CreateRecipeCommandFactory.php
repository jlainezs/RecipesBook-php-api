<?php
namespace App\Recipe\Application\Command\CreateRecipe;

use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;

class CreateRecipeCommandFactory
{
    public function __construct(
        public ApplicationDataValidator $validator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function createFromDto(CreateRecipeDto $dto): CreateRecipeCommand
    {
        $id = AggregateRootId::generateId();
        $cmd = new CreateRecipeCommand(
            $id->toString(),
            $dto->name,
            $dto->servings,
            $dto->rating,
            $dto->description,
            $dto->source,
            $dto->steps,
            $dto->ingredients
        );
        $this->validator->validate($cmd);

        return $cmd;
    }
}
