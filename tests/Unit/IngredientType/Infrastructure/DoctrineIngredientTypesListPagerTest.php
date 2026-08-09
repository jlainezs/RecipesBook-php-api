<?php

namespace App\Tests\Unit\IngredientType\Infrastructure;

use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\IngredientType\Infrastructure\DoctrineIngredientTypesListPager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DoctrineIngredientTypesListPagerTest extends TestCase
{
    private DoctrineIngredientTypesListPager $pager;
    private IngredientTypeRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(IngredientTypeRepositoryInterface::class);
        $this->pager = new DoctrineIngredientTypesListPager($this->repository);
    }

    #[Test]
    public function it_should_return_an_empty_list_when_there_are_no_ingredients(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $items = $this->pager->items(1, 10);
        $this->assertCount(0, $items);
    }
}
