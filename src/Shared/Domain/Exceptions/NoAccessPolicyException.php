<?php
namespace App\Shared\Domain\Exceptions;

use LogicException;

final class NoAccessPolicyException extends LogicException
{
    public function __construct(string $commandClass = '')
    {
        parent::__construct(sprintf(
            'The operation "%s" does not have an access policy defined (missing authorization roles or public operation).',
            $commandClass
        ));
    }
}
