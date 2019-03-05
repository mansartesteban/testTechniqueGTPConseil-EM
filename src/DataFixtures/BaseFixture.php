<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

abstract class BaseFixture extends Fixture
{
    /** @var Array */
    protected $iter = [];

    /** @var ObjectManager */
    private $manager;

    /** @var Generator */
    protected $faker;

    private $referencesIndex = [];

    abstract protected function loadData(ObjectManager $manager);

    public function  __construct()
    {
        $this->iter = [
            TaskFixture::class => 40,
            UserFixture::class => 4,
            EmployeeFixture::class => 9,
            UserSettingFixture::class => 1
        ];
    }

    public function load(ObjectManager $manager) {
        $this->manager = $manager;
        $this->faker = Factory::create("fr_FR");
        $this->loadData($manager);
    }

    protected function createMany(string $groupName, callable $factory, $count = null) {
        $count = $count ?? $this->iter[get_class($this)] ?? 1;
        for ($i = 0 ; $i < $count ; $i++) {
            $entity = $factory($i);
            if (null === $entity) {
                throw new \LogicException("Expect to get 'App\Entity', null given in \"BaseFixture::createMany()\"");
            }
            $this->manager->persist($entity);
            $j = -1;
            while ($this->hasReference(sprintf("%s_%d", $groupName, $i + ++$j))) {}
            $this->addReference(sprintf("%s_%d", $groupName, $i + $j), $entity);
        }
    }

    protected function getRandomReference(string $groupName) {
        if (!isset($this->referencesIndex[$groupName])) {
            $this->referencesIndex[$groupName] = [];
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $groupName.'_') === 0) {
                    $this->referencesIndex[$groupName][] = $key;
                }
            }
        }
        if (empty($this->referencesIndex[$groupName])) {
            throw new \InvalidArgumentException(sprintf('Cannot find any references for the reference group named "%s"', $groupName));
        }

        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$groupName]);
        return $this->getReference($randomReferenceKey);
    }

    public function getRandomReferences(string $group, int $count) {
        $references = [];
        while (count($references) < $count) {
            $references[] = $this->getReference($group);
        }
        return ($references);
    }

}