<?php
namespace App\Season\Application\Command\Season\CreateSeason;

use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;

class CreateSeasonCommandFactory
{
    public function __construct(
        public ApplicationDataValidator $validator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function createFromDto(CreateSeasonDto $createSeasonDto): CreateSeasonCommand
    {
        $cmd = new CreateSeasonCommand(
            AggregateRootId::generateId()->toString(),
            $createSeasonDto->name,
        );
        $this->validator->validate($cmd);

        return $cmd;
    }
}
