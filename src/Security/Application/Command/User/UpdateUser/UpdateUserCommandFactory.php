<?php

namespace App\Security\Application\Command\User\UpdateUser;

use App\Security\Domain\Model\UserRole;

class UpdateUserCommandFactory
{
    public function createFromRequestDto(UpdateUserRouteDto $userId, UpdateUserDto $dto): UpdateUserCommand
    {
        return  new UpdateUserCommand(
            $userId->id,
            $dto->email,
            $dto->password,
            $dto->firstName,
            $dto->lastName,
            array_map(fn(string $role): UserRole => UserRole::tryFrom($role), $dto->roles),
        );
    }
}
