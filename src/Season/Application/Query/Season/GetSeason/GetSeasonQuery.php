<?php
namespace App\Season\Application\Query\Season\GetSeason;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetSeasonQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
