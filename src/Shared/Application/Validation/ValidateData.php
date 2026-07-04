<?php
namespace App\Shared\Application\Validation;

use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

final readonly class ValidateData implements ApplicationDataValidator
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
