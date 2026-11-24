<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipes;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class GetRecipesQuery extends PaginatedListQuery
{}
