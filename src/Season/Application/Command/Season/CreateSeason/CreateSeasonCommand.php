<?php
namespace App\Season\Application\Command\Season\CreateSeason;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class CreateSeasonCommand
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name
    ){}
}
