<?php
namespace App\Season\Application\Query\Season\GetSeason;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class GetSeasonQuery
{
    public function __construct(
        public string $id
    ){}
}
