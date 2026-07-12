<?php

namespace App\Tests\Unit\Recipe\Application\Command\Recipe;

use App\Recipe\Application\Command\RecipeDelete\RecipeDeleteCommand;
use App\Recipe\Application\Command\RecipeDelete\RecipeDeleteCommandHandler;
use App\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RecipeDeleteCommandTest extends TestCase
{
    private RecipeRepositoryInterface&MockObject $repository;
    private RecipeDeleteCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(RecipeRepositoryInterface::class);
        $this->handler = new RecipeDeleteCommandHandler($this->repository);
    }

    #[Test]
    public function it_deletes_the_recipe(): void
    {
        $recipe = Recipe::create(
            "Recipe",2,4, '', '', []
        );
        $id = $recipe->getId()->toString();

        $this->repository
            ->method("findOne")
            ->with($id)
            ->willReturn($recipe);

        $this->repository
            ->expects($this->once())
            ->method("delete")
            ->with($recipe);
        ($this->handler)(new RecipeDeleteCommand($id));
    }

    #[Test]
    public function it_throws_when_the_recipe_is_not_found(): void
    {
        $recipe = Recipe::create(
            "Recipe",2,4, '', '', []
        );
        $id = $recipe->getId()->toString();

        $this->repository
            ->method("findOne")
            ->with($id)
            ->willReturn(null);

        $this->repository
            ->expects($this->never())
            ->method("delete");

        $this->expectException(RecipeNotFoundException::class);
        ($this->handler)(new RecipeDeleteCommand($id));
    }
}
