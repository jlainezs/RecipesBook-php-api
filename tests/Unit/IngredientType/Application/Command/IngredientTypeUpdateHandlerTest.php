<?php

namespace App\Tests\Unit\IngredientType\Application\Command;

use App\IngredientType\Application\Command\IngredientType\IngredientTypeUpdateCommand;
use App\IngredientType\Application\Command\IngredientType\IngredientTypeUpdateCommandHandler;
use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IngredientTypeUpdateHandlerTest extends TestCase
{
    private IngredientTypeRepositoryInterface&MockObject $repository;
    private IngredientTypeUpdateCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientTypeRepositoryInterface::class);
        $this->handler = new IngredientTypeUpdateCommandHandler($this->repository);
    }

    #[Test]
    public function it_renames_and_saves_when_ingredient_type_is_found(): void
    {
        $ingredientType = IngredientType::create('Vegetable');
        $id = $ingredientType->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($ingredientType);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($ingredientType);

        ($this->handler)(new IngredientTypeUpdateCommand($id, 'Fruit'));

        $this->assertSame('Fruit', $ingredientType->getName());
    }

    #[Test]
    public function it_throws_when_ingredient_type_is_not_found(): void
    {
        $id = '3fa85f64-5717-4562-b3fc-2c963f66afa6';

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn(null);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(IngredientTypeNotFoundException::class);

        ($this->handler)(new IngredientTypeUpdateCommand($id, 'Fruit'));
    }

    #[Test]
    public function it_throws_and_does_not_save_when_new_name_is_empty(): void
    {
        $ingredientType = IngredientType::create('Vegetable');
        $id = $ingredientType->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($ingredientType);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(IngredientTypeEmptyNameException::class);

        ($this->handler)(new IngredientTypeUpdateCommand($id, ''));
    }
}
