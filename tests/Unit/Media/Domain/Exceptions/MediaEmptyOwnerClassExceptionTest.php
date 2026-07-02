<?php
namespace App\Tests\Unit\Media\Domain\Exceptions;
use App\Media\Domain\Exceptions\MediaEmptyOwnerClassException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MediaEmptyOwnerClassExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new MediaEmptyOwnerClassException();
        $this->assertSame('Media owner class cannot be empty', $exception->getMessage());
    }
}
