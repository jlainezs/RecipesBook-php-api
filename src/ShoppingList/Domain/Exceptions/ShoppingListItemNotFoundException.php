<?php
namespace App\ShoppingList\Domain\Exceptions;

use Exception;

final class ShoppingListItemNotFoundException extends Exception
{
    public function __construct(string $requiredId)
    {
        $message = sprintf("Required shopping list item %s not found", $requiredId);
        parent::__construct($message);
    }
}
