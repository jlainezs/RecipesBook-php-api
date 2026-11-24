<?php
namespace App\Season\Application\Command\Season\CreateSeason;

use App\Shared\Application\Security\IsPublicOperation;
use Symfony\Component\Validator\Constraints as Assert;

#[IsPublicOperation]
readonly final class CreateSeasonCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name
    ){}
}
