<?php
namespace App\Tests\Unit\Ingredient\Domain\Model;

use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IngredientTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $itr = new IngredientTypeReference('c0740d3d-b189-4cf3-90eb-d3dd14f3a4c4');
        $ingredient = Ingredient::create('ingredient 1', 'description 1', $itr);

        $this->assertSame('ingredient 1', $ingredient->getName());
        $this->assertSame('description 1', $ingredient->getDescription());
        $this->assertSame($itr->value()->toString(), $ingredient->getIngredientType()->value()->toString());
        $this->assertInstanceOf(DateTimeImmutable::class, $ingredient->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $ingredient->getUpdatedAt());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_with_a_valid_ingredient_type(): void
    {
        $itr = new IngredientTypeReference('c0740d3d-b189-4cf3-90eb-d3dd14f3a4c4');
        $ingredient = Ingredient::create('ingredient 1', 'description 1', $itr);

        $this->assertSame($itr->value()->toString(), $ingredient->getIngredientType()->value()->toString());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $itr1 = new IngredientTypeReference('c0740d3d-b189-4cf3-90eb-d3dd14f3a4c4');
        $i1 = Ingredient::create('ingredient 1', 'description 1', $itr1);

        $itr2 = new IngredientTypeReference('c0740d3d-b189-4cf3-90eb-d3dd14f3a4c4');
        $i2 = Ingredient::create('ingredient 2', 'description 2', $itr2);

        $this->assertNotSame($i1->getId()->toString(), $i2->getId()->toString());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_on_empty_name(): void
    {
        $itr = new IngredientTypeReference('c0740d3d-b189-4cf3-90eb-d3dd14f3a4c4');
        $this->expectException(EmptyRequiredNameException::class);
        Ingredient::create('', 'description 1', $itr);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_on_whitespace_name(): void
    {
        $itr = new IngredientTypeReference('c0740d3d-b189-4cf3-90eb-d3dd14f3a4c4');
        $this->expectException(EmptyRequiredNameException::class);
        Ingredient::create('   ', 'description 1', $itr);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_renames_successfully(): void
    {
        $itr = new IngredientTypeReference('c0740d3d-b189-4cf3-90eb-d3dd14f3a4c4');
        $ingredient = Ingredient::create('ingredient 1', 'description 1', $itr);
        $ingredient->rename('ingredient 2');
        $this->assertSame('ingredient 2', $ingredient->getName());
    }

    #[Test]
    public function it_throws_on_rename_with_empty_name(): void
    {
        $itr = new IngredientTypeReference('c0740d3d-b189-4cf3-90eb-d3dd14f3a4c4');
        $ingredient = Ingredient::create('ingredient 1', 'description 1', $itr);
        $this->expectException(EmptyRequiredNameException::class);
        $ingredient->rename('');
    }

    #[Test]
    public function it_throws_on_rename_with_whitespace(): void
    {
        $itr = new IngredientTypeReference('c0740d3d-b189-4cf3-90eb-d3dd14f3a4c4');
        $ingredient = Ingredient::create('ingredient 1', 'description 1', $itr);
        $this->expectException(EmptyRequiredNameException::class);
        $ingredient->rename('    ');
    }
}
