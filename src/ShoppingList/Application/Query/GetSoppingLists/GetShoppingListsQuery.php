<?php
namespace App\ShoppingList\Application\Query\GetSoppingLists;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class GetShoppingListsQuery extends PaginatedListQuery
{
}
