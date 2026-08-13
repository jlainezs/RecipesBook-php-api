<?php
namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\EmptyRequiredNameException;

final readonly class RequiredName
{
    private string $value;

    /**
     * @param string $name
     * @throws EmptyRequiredNameException
     */
    public function __construct(string $name)
    {
        if (empty(trim($name)))
        {
            throw new EmptyRequiredNameException();
        }

        $this->value = $name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
