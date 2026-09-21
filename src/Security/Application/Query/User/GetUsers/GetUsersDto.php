<?php
namespace App\Security\Application\Query\User\GetUsers;

final readonly class GetUsersDto
{
    public function __construct(
        public int $offset = 0,
        public int $limit = 10,
    ){}
}
