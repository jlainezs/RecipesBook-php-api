<?php
namespace App\Tests\Unit\Recipe\Application\Command\Recipe;

use App\Recipe\Application\Command\Recipe\RecipeCreateCommand;
use App\Recipe\Application\Command\Recipe\RecipeCreateCommandHandler;
use App\Recipe\Domain\Exceptions\RecipeEmptyNameException;
use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeCreateCommandHandlerTest extends TestCase
{
    private RecipeRepositoryInterface $repository;
    private RecipeCreateCommandHandler $handler;

    public function setUp(): void
    {
        $this->repository = $this->createMock(RecipeRepositoryInterface::class);
        $this->handler = new RecipeCreateCommandHandler($this->repository);
    }

    /**
     * @throws RecipeInvalidServingsException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_and_saves_the_recipe(): void
    {
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Recipe::class));
        ($this->handler)(new RecipeCreateCommand(
            "name",
            1,
            1,
            "description",
            "source"
        ));
    }

    #[Test]
    public function it_throws_and_does_not_saves_when_name_is_empty(): void
    {
        $this->repository->expects($this->never())
            ->method('save');
        $this->expectException(RecipeEmptyNameException::class);
        ($this->handler)(new RecipeCreateCommand(
            "",
            1,
            1,
            "description",
            "source"
        ));
    }

    #[Test]
    public function it_throws_and_does_not_saves_when_name_is_whitespace_only(): void
    {
        $this->repository->expects($this->never())
            ->method('save');
        $this->expectException(RecipeEmptyNameException::class);
        ($this->handler)(new RecipeCreateCommand(
            " ",
            1,
            1,
            "description",
            "source"
        ));
    }
}
