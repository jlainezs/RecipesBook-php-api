<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UnitOfMeasureDeleteCommandHandler
{
    public function __construct(private UnitOfMeasureRepositoryInterface $repository)
    {}

    /**
     * @throws UnitOfMeasureNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(UnitOfMeasureDeleteCommand $command): void
    {
        if ($uom = $this->repository->findOne(new AggregateRootId($command->id)))
        {
            $this->repository->delete($uom);
        } else {
            throw new UnitOfMeasureNotFoundException($command->id);
        }
    }
}
