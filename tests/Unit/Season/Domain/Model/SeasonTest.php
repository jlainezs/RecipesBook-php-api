<?php
namespace App\Tests\Unit\Season\Domain\Model;

use App\Season\Domain\Model\Season;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SeasonTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $name = "Valid Season Name";
        $season = Season::create($name);
        $this->assertEquals($name, $season->getName());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $season1 = Season::create("Season 1");
        $season2 = Season::create("Season 2");
        $this->assertNotEquals($season1->getId(), $season2->getId());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_on_empty_name(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        Season::create("");
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_on_whitespace_name(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        Season::create("   ");
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_renames_successfully(): void
    {
        $season = Season::create("Old Season Name");
        $newName = "New Season Name";
        $season->rename($newName);
        $this->assertEquals($newName, $season->getName());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_rename_with_empty_name(): void
    {
        $season = Season::create("Old Season Name");
        $this->expectException(EmptyRequiredNameException::class);
        $season->rename("");
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_rename_with_white_space_name(): void
    {
        $season = Season::create("Old Season Name");
        $this->expectException(EmptyRequiredNameException::class);
        $season->rename("   ");
    }
}
