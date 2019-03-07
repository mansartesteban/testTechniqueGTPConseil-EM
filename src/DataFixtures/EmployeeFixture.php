<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Common\Persistence\ObjectManager;

class EmployeeFixture extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany("Employee", function ($i) {
           $employee = new Employee();
           $employee
               ->setEmail("emp" . $i . "@gtp-conseil.fr")
               ->setRoles(["ROLE_EMPLOYEE"])
               ->setColor("rgb(".rand(128,255).",".rand(128,255).",".rand(128,255).")")
               ->setPassword("$2y$10$/k3eAc9k7VID8AmJAzhJV.ogJRDWlIGgJXjHseid7fWNMYMD8Y/wa"); //GTPConseil12345
           return $employee;
        });
        $manager->flush();
    }
}
