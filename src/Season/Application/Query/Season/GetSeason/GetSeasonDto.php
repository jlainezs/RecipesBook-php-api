<?php
namespace App\Season\Application\Query\Season\GetSeason;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetSeasonDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
