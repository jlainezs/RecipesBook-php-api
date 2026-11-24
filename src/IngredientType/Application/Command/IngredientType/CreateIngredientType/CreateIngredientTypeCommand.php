<?php
namespace App\IngredientType\Application\Command\IngredientType\CreateIngredientType;

use App\Shared\Application\Security\IsPublicOperation;
use Symfony\Component\Validator\Constraints as Assert;

#[IsPublicOperation]
readonly final class CreateIngredientTypeCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name
    )
    {}
}
