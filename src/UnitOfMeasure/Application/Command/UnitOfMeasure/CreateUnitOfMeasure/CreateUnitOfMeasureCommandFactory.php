<?php

namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure;

use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;

class CreateUnitOfMeasureCommandFactory
{
    public function __construct(
        public ApplicationDataValidator $validator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function createFromDto(CreateUnitOfMeasureDto $dto): CreateUnitOfMeasureCommand
    {
        $id = AggregateRootId::generateId();
        $cmd = new CreateUnitOfMeasureCommand(
            $id->toString(),
            $dto->name,
            $dto->symbol,
            UnitOFMeasureEnum::from($dto->unitOfMeasureType)
        );
        $this->validator->validate($cmd);

        return $cmd;
    }
}
