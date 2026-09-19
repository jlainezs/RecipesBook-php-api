<?php
namespace App\Security\Application\Query\User\GetUser;

use App\Security\Application\Query\User\UserDto;

final readonly class GetUserQueryResponse
{
    public function __construct(
        public UserDto $user,
    ){}
}
