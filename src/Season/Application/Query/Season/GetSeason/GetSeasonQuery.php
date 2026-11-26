<?php
namespace App\Season\Application\Query\Season\GetSeason;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
final readonly class GetSeasonQuery
{
    public function __construct(
        public string $id
    ){}
}
