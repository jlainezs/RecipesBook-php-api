<?php
namespace App\Tests\DataFixtures\Season;

use App\Season\Domain\Model\Season;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture
{
    public array $testData;
    public function __construct()
    {
        $this->testData = [];
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function load(ObjectManager $manager): void
    {
        $rawObjects = [
            [
                'name' => 'Test season 1',
            ],
            [
                'name' => 'Test season 2',
            ],
            [
                'name' => 'Test season 3',
            ],
        ];

        foreach ($rawObjects as $rawObject)
        {
            $season = Season::create($rawObject['name']);
            $this->testData[] = $season;
            $manager->persist($season);
        }

        $manager->flush();
    }
}
