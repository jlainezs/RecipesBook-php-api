<?php
namespace App\Security\Application\Query\User\GetUsers;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\IsPublicOperation;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly class GetUsersQuery extends PaginatedListQuery
{}
