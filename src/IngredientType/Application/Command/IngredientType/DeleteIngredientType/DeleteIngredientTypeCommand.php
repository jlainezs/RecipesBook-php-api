<?php
namespace App\IngredientType\Application\Command\IngredientType\DeleteIngredientType;

use App\Shared\Application\Security\IsAuthorized;
use App\Shared\Application\Security\SecurityRole;

#[IsAuthorized(SecurityRole::USER)]
readonly final class DeleteIngredientTypeCommand
{
    public function __construct(
        public  string $id
    ){}
}
