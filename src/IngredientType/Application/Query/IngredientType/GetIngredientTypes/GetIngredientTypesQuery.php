<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientTypes;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly class GetIngredientTypesQuery extends PaginatedListQuery
{}
