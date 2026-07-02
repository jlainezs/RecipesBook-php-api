<?php
namespace App\Tests\Unit\Media\Domain\Exceptions;

use App\Media\Domain\Exceptions\MediaNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MediaNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        // a random UUID
        $requiredId = '30411df1-8af8-42dc-a3ab-23ce962b7695';
        $expectedMessage = sprintf('Media with id %s not found', $requiredId);

        $exception = new MediaNotFoundException($requiredId);
        $this->assertSame($expectedMessage, $exception->getMessage());
    }
}
