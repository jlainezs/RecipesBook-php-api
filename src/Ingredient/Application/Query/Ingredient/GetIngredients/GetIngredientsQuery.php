<?php
namespace App\Ingredient\Application\Query\Ingredient\GetIngredients;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly class GetIngredientsQuery extends PaginatedListQuery
{}
