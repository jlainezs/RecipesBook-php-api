<?php
namespace App\Season\Application\Command\Season\UpdateSeason;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateSeasonRouteDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
