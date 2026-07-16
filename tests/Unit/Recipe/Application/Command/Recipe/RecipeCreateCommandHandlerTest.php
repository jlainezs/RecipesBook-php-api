<?php
namespace App\Tests\Unit\Recipe\Application\Command\Recipe;

use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Recipe\Application\Command\RecipeCreate\RecipeCreateCommand;
use App\Recipe\Application\Command\RecipeCreate\RecipeCreateCommandHandler;
use App\Recipe\Domain\Exceptions\RecipeEmptyNameException;
use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RecipeCreateCommandHandlerTest extends TestCase
{
    private RecipeRepositoryInterface&MockObject $repository;
    private IngredientRepositoryInterface&MockObject $ingredientrepository;
    private UnitOfMeasureRepositoryInterface&MockObject $unitofmeasurerepository;
    private RecipeCreateCommandHandler $handler;

    public function setUp(): void
    {
        $this->repository = $this->createMock(RecipeRepositoryInterface::class);
        $this->ingredientrepository = $this->createMock(IngredientRepositoryInterface::class);
        $this->unitofmeasurerepository = $this->createMock(UnitOfMeasureRepositoryInterface::class);
        $this->handler = new RecipeCreateCommandHandler(
            $this->repository,
            $this->ingredientrepository,
            $this->unitofmeasurerepository
        );
    }

    /**
     * @throws RecipeInvalidServingsException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_and_saves_the_recipe_without_steps(): void
    {
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Recipe::class));
        ($this->handler)(new RecipeCreateCommand(
            "name",
            1,
            1,
            "description",
            "source",
            [],
            []
        ));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
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
            "source",
            [],
            []
        ));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
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
            "source",
            [],
            []
        ));
    }
}
