<?php
namespace App\Tests\Unit\UnitOfMeasure\Application\Command\UnitOfMeasureUpdate;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UnitOfMeasureUpdateCommand;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\UnitOfMeasureUpdateCommandHandler;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitOfMeasureUpdateCommandHandlerTest extends TestCase
{
    private UnitOfMeasureUpdateCommandHandler $handler;
    private UnitOfMeasureRepositoryInterface $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UnitOfMeasureRepositoryInterface::class);
        $this->handler = new UnitOfMeasureUpdateCommandHandler($this->repository);
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws UnitOfMeasureNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_update_unit_of_measure():void
    {
        $unitOfMeasure = UnitOfMeasure::create(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $id = $unitOfMeasure->getId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($unitOfMeasure);
        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($unitOfMeasure);
        $cmd = new UnitOfMeasureUpdateCommand(
            id: $id->toString(),
            name: 'new name',
            symbol: 't',
            unitOfMeasureEnum: UnitOfMeasureEnum::Units
        );
        ($this->handler)($cmd);
        $this->assertSame('new name', $unitOfMeasure->getName());
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     * @throws UnitOfMeasureNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_setting_empty_name(): void
    {
        $unitOfMeasure = UnitOfMeasure::create(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: UnitOfMeasureEnum::Units
        );
        $id = $unitOfMeasure->getId();
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn($unitOfMeasure);
        $this->repository
            ->expects($this->never())
            ->method('save')
            ->with($unitOfMeasure);
        $cmd = new UnitOfMeasureUpdateCommand(
            id: $id->toString(),
            name: '',
            symbol: 't',
            unitOfMeasureEnum: UnitOfMeasureEnum::Units
        );
        $this->expectException(EmptyRequiredNameException::class);
        ($this->handler)($cmd);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_throws_when_shopping_list_is_not_found(): void
    {
        $id = AggregateRootId::generateId();
        $cmd = new UnitOfMeasureUpdateCommand(
            id: $id->toString(),
            name: 'new name',
            symbol: 't',
            unitOfMeasureEnum: UnitOfMeasureEnum::Units
        );
        $this->repository
            ->expects($this->once())
            ->method('findOne')
            ->with($id)
            ->willReturn(null);
        $this->repository
            ->expects($this->never())
            ->method('save');
        $this->expectException(UnitOfMeasureNotFoundException::class);
        ($this->handler)($cmd);
    }
}
