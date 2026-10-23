<?php
namespace App\Tests\DataFixtures\UnitOfMeasure;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UnitOfMeasureFixture extends Fixture
{
    public array $testData;

    public function __construct()
    {
        $this->testData = [];
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws UnitOfMeasureSymbolLengthException
     */
    public function load(ObjectManager $manager): void
    {
        $toCreate = [
            [AggregateRootId::generateId()->toString(), 'test unit of measure 1', 's1', UnitOfMeasureEnum::Units],
            [AggregateRootId::generateId()->toString(), 'test unit of measure 2', 's2', UnitOfMeasureEnum::Weight],
            [AggregateRootId::generateId()->toString(), 'test unit of measure 3', 's3', UnitOfMeasureEnum::Volume],
        ];

        foreach ($toCreate as $uom)
        {
            $unit = UnitOfMeasure::create(
                $uom[0], $uom[1], $uom[2], $uom[3]
            );
            $this->testData[] = $unit;
            $manager->persist($unit);
        }

        $manager->flush();
    }
}
