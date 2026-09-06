<?php

namespace App\Tests\Unit\IngredientType\Application\Command;

use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeCommandHandler;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyRequiredNameException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateIngredientTypeHandlerTest extends TestCase
{
    private IngredientTypeRepositoryInterface&MockObject $repository;
    private CreateIngredientTypeCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientTypeRepositoryInterface::class);
        $this->handler = new CreateIngredientTypeCommandHandler($this->repository);
    }

    #[Test]
    public function it_creates_and_saves_the_ingredient_type(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(IngredientType::class));

        ($this->handler)(new CreateIngredientTypeCommand('Vegetable'));
    }

    #[Test]
    public function it_throws_and_does_not_save_when_name_is_empty(): void
    {
        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(EmptyRequiredNameException::class);

        ($this->handler)(new CreateIngredientTypeCommand(''));
    }

    #[Test]
    public function it_throws_and_does_not_save_when_name_is_whitespace_only(): void
    {
        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(EmptyRequiredNameException::class);

        ($this->handler)(new CreateIngredientTypeCommand('   '));
    }
}
