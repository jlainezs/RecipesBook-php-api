<?php
namespace App\Security\Application\Command\User\DeleteUser;

class DeleteUserCommandFactory
{
    public function createFromDto(DeleteUserDto $dto): DeleteUserCommand
    {
        return new DeleteUserCommand($dto->id);
    }
}
