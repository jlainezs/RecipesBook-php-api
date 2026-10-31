<?php
namespace App\Security\Application\Query\User\GetUser;

class GetUserQueryFactory
{
    public function createFromDto(GetUserRouteDto $dto): GetUserQuery
    {
        return new GetUserQuery($dto->id);
    }
}
