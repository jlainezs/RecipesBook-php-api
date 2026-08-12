<?php

namespace App\Tests\Unit\Season\Domain\Exceptions;

use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SeasonEmptyNameExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new SeasonEmptyNameException();
        $this->assertEquals('Season name cannot be empty', $exception->getMessage());
    }
}
