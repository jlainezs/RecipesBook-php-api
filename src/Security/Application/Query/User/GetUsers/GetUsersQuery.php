<?php
namespace App\Security\Application\Query\User\GetUsers;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly class GetUsersQuery extends PaginatedListQuery
{
}
