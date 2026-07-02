<?php
namespace App\Tests\Unit\Media\Domain\Exceptions;

use App\Media\Domain\Exceptions\MediaEmptyUriException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MediaEmptyUriExceptionTest extends TestCase
{
    #[Test]
    public function it_have_a_descriptive_message(): void
    {
        $exception = new MediaEmptyUriException();
        $this->assertSame('Media uri cannot be empty', $exception->getMessage());
    }
}
