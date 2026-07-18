<?php
namespace App\Shared\Domain\Exception;

use InvalidArgumentException;

final class EmptyRequiredNameException extends InvalidArgumentException
{
    public function __construct(){
        parent::__construct("A required name cannot be empty");
    }
}
