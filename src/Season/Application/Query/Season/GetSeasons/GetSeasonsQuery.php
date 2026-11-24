<?php
namespace App\Season\Application\Query\Season\GetSeasons;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
readonly class GetSeasonsQuery extends PaginatedListQuery
{
}
