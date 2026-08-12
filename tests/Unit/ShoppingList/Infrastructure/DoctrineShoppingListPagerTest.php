<?php
namespace App\Tests\Unit\ShoppingList\Infrastructure;

use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use App\ShoppingList\Infrastructure\DoctrineShoppingListPager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DoctrineShoppingListPagerTest extends TestCase
{
    private DoctrineShoppingListPager $pager;
    private ShoppingListRepositoryInterface $repository;

    public function setUp(): void
    {
        $this->repository = $this->createMock(ShoppingListRepositoryInterface::class);
        $this->pager = new DoctrineShoppingListPager($this->repository);
    }

    #[Test]
    public function it_should_return_an_empty_list_when_there_are_no_seasons()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $result = $this->pager->items(1, 10);
        $this->assertCount(0, $result);
    }
}
