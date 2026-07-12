<?php
namespace App\Tests\Unit\Recipe\Application\Command\Recipe;

use App\Recipe\Application\Command\RecipeUpdate\RecipeUpdateCommand;
use App\Recipe\Application\Command\RecipeUpdate\RecipeUpdateCommandHandler;
use App\Recipe\Domain\Exceptions\RecipeEmptyNameException;
use App\Recipe\Domain\Exceptions\RecipeInvalidRatingException;
use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RecipeUpdateCommandHandlerTest extends TestCase
{
    private RecipeRepositoryInterface&MockObject $repository;
    private RecipeUpdateCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(RecipeRepositoryInterface::class);
        $this->handler = new RecipeUpdateCommandHandler($this->repository);
    }

    /**
     * @throws RecipeInvalidServingsException
     * @throws RecipeNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_update_recipe(): void
    {
        $recipe = Recipe::create('a recipe', 20, 4, '', '', []);
        $id = $recipe->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($recipe);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($recipe);
        ($this->handler)(new RecipeUpdateCommand(
            $id,
            'modified recipe',
            4,
            5,

            "A description",
            "A source",
            []
        ));

        $this->assertSame('modified recipe', $recipe->getName());
        $this->assertSame(4, $recipe->getServings());
        $this->assertSame(5, $recipe->getRating());
        $this->assertSame("A description", $recipe->getDescription());
        $this->assertSame("A source", $recipe->getSource());
    }

    /**
     * @throws RecipeInvalidServingsException
     * @throws RecipeNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_setting_empty_name(): void
    {
        $recipe = Recipe::create('a recipe', 20, 4, '', '', []);
        $id = $recipe->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($recipe);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(RecipeEmptyNameException::class);
        ($this->handler)(new RecipeUpdateCommand(
            $id,
            '',
            4,
            5,
            "A description",
            "A source",
            []
        ));
    }


    /**
     * @throws RecipeInvalidServingsException
     * @throws EmptyIdNotAllowedException
     * @throws RecipeNotFoundException
     */
    #[Test]
    public function it_throws_when_setting_whitespace_only__name(): void
    {
        $recipe = Recipe::create('a recipe', 20, 4, '', '', []);
        $id = $recipe->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($recipe);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(RecipeEmptyNameException::class);
        ($this->handler)(new RecipeUpdateCommand(
            $id,
            ' ',
            4,
            5,
            "A description",
            "A source",
            []
        ));
    }

    /**
     * @throws RecipeInvalidServingsException
     * @throws RecipeNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_setting_invalid_upper_rating(): void
    {
        $recipe = Recipe::create('a recipe', 20, 4, '', '', []);
        $id = $recipe->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($recipe);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(RecipeInvalidRatingException::class);
        ($this->handler)(new RecipeUpdateCommand(
            $id,
            'a modified name',
            4,
            10,
            "A description",
            "A source",
            []
        ));
    }

    /**
     * @throws RecipeInvalidServingsException
     * @throws EmptyIdNotAllowedException
     * @throws RecipeNotFoundException
     */
    #[Test]
    public function it_throws_when_setting_invalid_lower_rating(): void
    {
        $recipe = Recipe::create('a recipe', 20, 4, '', '', []);
        $id = $recipe->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($recipe);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(RecipeInvalidRatingException::class);
        ($this->handler)(new RecipeUpdateCommand(
            $id,
            'a modified name',
            4,
            -1,
            "A description",
            "A source",
            []
        ));
    }

    /**
     * @throws RecipeInvalidServingsException
     * @throws EmptyIdNotAllowedException
     * @throws RecipeNotFoundException
     */
    #[Test]
    public function it_throws_when_setting_invalid_servings(): void
    {
        $recipe = Recipe::create('a recipe', 20, 4, '', '', []);
        $id = $recipe->getId()->toString();

        $this->repository
            ->method('findOne')
            ->with($id)
            ->willReturn($recipe);

        $this->repository
            ->expects($this->never())
            ->method('save');

        $this->expectException(RecipeInvalidServingsException::class);
        ($this->handler)(new RecipeUpdateCommand(
            $id,
            'a modified name',
            0,
            5,
            "A description",
            "A source",
            []
        ));
    }
}
