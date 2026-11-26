<?php
namespace App\Season\Application\Query\Season\GetSeasons;

use App\Shared\Application\Query\PaginatedList\PaginatedListQuery;
use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly class GetSeasonsQuery extends PaginatedListQuery
{}
