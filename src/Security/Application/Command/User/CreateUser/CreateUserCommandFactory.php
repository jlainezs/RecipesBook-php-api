<?php
namespace App\Security\Application\Command\User\CreateUser;

use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;

class CreateUserCommandFactory
{
    public function __construct(
        public ApplicationDataValidator $validator
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function createFromDto(CreateUserDto $dto): CreateUserCommand
    {
        $cmd = new CreateUserCommand(
            AggregateRootId::generateId()->toString(),
            $dto->email,
            $dto->password,
            $dto->firstName,
            $dto->lastName,
            $dto->roles
        );
        $this->validator->validate($cmd);

        return $cmd;
    }
}
