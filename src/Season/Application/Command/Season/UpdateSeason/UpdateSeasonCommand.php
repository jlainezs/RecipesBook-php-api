<?php
namespace App\Season\Application\Command\Season\UpdateSeason;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateSeasonCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public readonly string $name
    ){}
}
