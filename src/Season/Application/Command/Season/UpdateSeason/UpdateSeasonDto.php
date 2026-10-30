<?php
namespace App\Season\Application\Command\Season\UpdateSeason;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateSeasonDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name
    ){}
}
