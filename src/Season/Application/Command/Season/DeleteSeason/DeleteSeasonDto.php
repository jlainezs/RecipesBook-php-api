<?php
namespace App\Season\Application\Command\Season\DeleteSeason;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteSeasonDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id,
    ) {}
}
