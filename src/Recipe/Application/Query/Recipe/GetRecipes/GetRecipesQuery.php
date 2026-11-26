<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipes;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\IsPublicOperation;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
final readonly class GetRecipesQuery extends PaginatedListQuery
{}
