<?php
namespace App\Shared\Application\Validation;

use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

final readonly class ApplicationDataValidator
{
    public function __construct(private ValidatorInterface $validator)
    {}

    function validate($object): void
    {
        $errors = $this->validator->validate($object);
        if ($errors->count() > 0)
        {
            throw new ValidationFailedException($errors, $errors);
        }
    }
}
