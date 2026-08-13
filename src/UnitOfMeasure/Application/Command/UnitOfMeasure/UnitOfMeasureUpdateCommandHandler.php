<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptyNameException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptySymbolException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class UnitOfMeasureUpdateCommandHandler
{
    public function __construct(private UnitOfMeasureRepositoryInterface $repository)
    {}

    /**
     * @throws UnitOfMeasureEmptyNameException
     * @throws UnitOfMeasureEmptySymbolException
     * @throws UnitOfMeasureNotFoundException
     * @throws EmptyIdNotAllowedException
     * @throws UnitOfMeasureSymbolLengthException
     * @throws EmptyRequiredNameException
     */
    public function __invoke(UnitOfMeasureUpdateCommand $command): void
    {
        if ($uom = $this->repository->findOne(new AggregateRootId($command->id)))
        {
            $uom->rename($command->name);
            $uom->changeSymbol($command->symbol);
            $uom->setUomType($command->unitOfMeasureEnum);
            $this->repository->save($uom);
        }
        else
        {
            throw new UnitOfMeasureNotFoundException($command->id);
        }
    }

}
