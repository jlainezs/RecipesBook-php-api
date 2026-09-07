<?php
namespace App\Season\Application\Command\Season\DeleteSeason;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class SeasonDeleteCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
