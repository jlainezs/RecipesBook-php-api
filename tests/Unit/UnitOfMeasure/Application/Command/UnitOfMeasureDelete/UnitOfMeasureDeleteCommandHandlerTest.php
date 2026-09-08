<?php
namespace App\Tests\Unit\UnitOfMeasure\Application\Command\UnitOfMeasureDelete;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure\DeleteUnitOfMeasureCommand;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure\DeleteUnitOfMeasureCommandHandler;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitOfMeasureDeleteCommandHandlerTest extends TestCase
{
    private UnitOfMeasureRepositoryInterface $repository;
    private DeleteUnitOfMeasureCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UnitOfMeasureRepositoryInterface::class);
        $this->handler = new DeleteUnitOfMeasureCommandHandler($this->repository);
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyIdNotAllowedException
     * @throws UnitOfMeasureNotFoundException
     */
    #[Test]
    public function it_deletes_the_unit_of_measure(): void
    {
        $uom = UnitOfMeasure::create(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $id = $uom->getId()->toString();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($uom);
        $this->repository
            ->expects($this->once())
            ->method('delete')
            ->with($uom);
        $command = new DeleteUnitOfMeasureCommand($id);
        ($this->handler)($command);
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws UnitOfMeasureNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_the_unit_of_measure_not_found(): void
    {
        $uom = UnitOfMeasure::create(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $id = $uom->getId()->toString();

        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);
        $this->repository
            ->expects($this->never())
            ->method('delete');
        $command = new DeleteUnitOfMeasureCommand($id);

        $this->expectException(UnitOfMeasureNotFoundException::class);
        ($this->handler)($command);
    }
}
