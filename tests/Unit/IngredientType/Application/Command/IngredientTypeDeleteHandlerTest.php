<?php

namespace App\Tests\Unit\IngredientType\Application\Command;

use App\IngredientType\Application\Command\IngredientType\IngredientTypeDeleteCommand;
use App\IngredientType\Application\Command\IngredientType\IngredientTypeDeleteCommandHandler;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IngredientTypeDeleteHandlerTest extends TestCase
{
    private IngredientTypeRepositoryInterface&MockObject $repository;
    private IngredientTypeDeleteCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientTypeRepositoryInterface::class);
        $this->handler = new IngredientTypeDeleteCommandHandler($this->repository);
    }

    #[Test]
    public function it_deletes_when_ingredient_type_is_found(): void
    {
        $ingredientType = IngredientType::create('Vegetable');
        $id = $ingredientType->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($ingredientType);

        $this->repository
            ->expects($this->once())
            ->method('delete')
            ->with($ingredientType);

        ($this->handler)(new IngredientTypeDeleteCommand($id));
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
            ->method('delete');

        $this->expectException(IngredientTypeNotFoundException::class);

        ($this->handler)(new IngredientTypeDeleteCommand($id));
    }
}
