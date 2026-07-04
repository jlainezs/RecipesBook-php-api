<?php
namespace App\Season\Application\Query\Season;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class SeasonInstanceQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
