<?php
namespace App\Recipe\Application\Command\Recipe;

use Symfony\Component\Validator\Constraints as Assert;
final readonly class RecipeDeleteCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
