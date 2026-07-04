<?php
namespace App\Season\Application\Command\Season;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class SeasonUpdateCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public readonly string $name
    ){}
}
