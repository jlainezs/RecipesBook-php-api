<?php
namespace App\Tests\Unit\Ingredient\Infrastructure;

use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Ingredient\Infrastructure\DoctrineIngredientsListPager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DoctrineIngredientsListPagerTest extends TestCase
{
    private DoctrineIngredientsListPager $pager;
    private IngredientRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IngredientRepositoryInterface::class);
        $this->pager = new DoctrineIngredientsListPager($this->repository);
    }

    #[Test]
    public function it_should_return_an_empty_list_when_there_are_no_ingredients(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $items = $this->pager->items(1, 10);
        $this->assertCount(0, $items);
    }
}
