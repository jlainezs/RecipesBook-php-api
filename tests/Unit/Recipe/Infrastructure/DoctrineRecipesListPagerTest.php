<?php
namespace App\Tests\Unit\Recipe\Infrastructure;

use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Recipe\Infrastructure\DoctrineRecipesListPager;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DoctrineRecipesListPagerTest extends TestCase
{
    private DoctrineRecipesListPager $pager;
    private RecipeRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(RecipeRepositoryInterface::class);
        $this->pager = new DoctrineRecipesListPager($this->repository);
    }


    #[Test]
    public function it_should_return_an_empty_list_when_there_are_no_ingredients()
    {
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $result = $this->pager->items(1, 10);

        $this->assertCount(0, $result);
    }
}
