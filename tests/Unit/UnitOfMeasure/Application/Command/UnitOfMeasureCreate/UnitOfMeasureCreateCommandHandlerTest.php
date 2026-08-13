<?php
namespace App\Tests\Unit\UnitOfMeasure\Application\Command\UnitOfMeasureCreate;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UnitOfMeasureCreateCommand;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UnitOfMeasureCreateCommandHandler;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitOfMeasureCreateCommandHandlerTest extends TestCase
{
    private UnitOfMeasureRepositoryInterface $repository;
    private UnitOfMeasureCreateCommandHandler $handler;

    public function setUp(): void
    {
        $this->repository = $this->createMock(UnitOfMeasureRepositoryInterface::class);
        $this->handler = new UnitOfMeasureCreateCommandHandler($this->repository);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_and_saves_the_unit_of_measure(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(UnitOfMeasure::class));
        ($this->handler)(new UnitOfMeasureCreateCommand(
            name: 'unit',
            symbol: 'u',
            unitOfMeasureEnum: UnitOfMeasureEnum::Units
        ));
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_and_does_not_saves_when_name_is_empty(): void
    {
        $this->repository
            ->expects($this->never())
            ->method('save')
            ->with($this->isInstanceOf(UnitOfMeasure::class));
        $this->expectException(EmptyRequiredNameException::class);
        ($this->handler)(new UnitOfMeasureCreateCommand(
            name: '',
            symbol: 'u',
            unitOfMeasureEnum: UnitOfMeasureEnum::Units
        ));
    }
}
