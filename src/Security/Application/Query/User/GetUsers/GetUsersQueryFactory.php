<?php
namespace App\Security\Application\Query\User\GetUsers;

class GetUsersQueryFactory
{
    public function createFromDto(GetUsersDto $dto): GetUsersQuery
    {
        return new GetUsersQuery($dto->offset, $dto->limit);
    }
}
