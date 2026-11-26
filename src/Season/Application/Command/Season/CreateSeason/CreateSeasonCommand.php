<?php
namespace App\Season\Application\Command\Season\CreateSeason;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;
use Symfony\Component\Validator\Constraints as Assert;

#[IsAuthorized(SecurityRole::USER)]
readonly final class CreateSeasonCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name
    ){}
}
