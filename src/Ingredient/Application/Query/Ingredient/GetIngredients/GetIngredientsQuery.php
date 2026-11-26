<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredients;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly class GetIngredientsQuery extends PaginatedListQuery
{}
