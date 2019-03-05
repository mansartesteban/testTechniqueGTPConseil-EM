<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixture extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany("Task", function () {
            $task = new Task();
            $startAt = $this->faker->dateTimeBetween("+2 hours", "+ 3 days");
            $task
                ->setLabel($this->faker->sentence(rand(2, 10)))
                ->setStartAt($startAt)
                ->setEndAt($startAt->modify("+" . rand(0, 8) . " hours"))
                ->setDone(0)
                ->setEmployee($this->getRandomReference("Employee"))
                ->setCreatedBy($this->getRandomReference("User"));
            return $task;
        });

        $manager->flush();
    }


    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixture::class,
            EmployeeFixture::class
        ];
    }
}
