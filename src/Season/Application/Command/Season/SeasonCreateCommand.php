<?php
namespace App\Season\Application\Command\Season;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class SeasonCreateCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name
    ){}
}
