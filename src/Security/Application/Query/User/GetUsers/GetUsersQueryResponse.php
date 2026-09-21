<?php
namespace App\Security\Application\Query\User\GetUsers;

use App\Security\Application\Query\User\UserDto;

final readonly class GetUsersQueryResponse
{
    public function __construct(
        /**
         * @var UserDto[]
         */
        public array $items,
    ){}
}
