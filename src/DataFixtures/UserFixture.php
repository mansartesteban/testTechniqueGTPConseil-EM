<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany("User", function () {
            $user = new User();
            $user
                ->setEmail("esteban@gtp-conseil.fr")
                ->setPassword("$2y$10$/k3eAc9k7VID8AmJAzhJV.ogJRDWlIGgJXjHseid7fWNMYMD8Y/wa") //GTPConseil12345
                ->setRoles(["ROLE_ADMIN"]);
            return $user;
        }, 1);

        $this->createMany("User", function ($i) {
            $user = new User();
            $user
                ->setEmail("admin" . $i ."@gtp-conseil.fr")
                ->setPassword("$2y$10$/k3eAc9k7VID8AmJAzhJV.ogJRDWlIGgJXjHseid7fWNMYMD8Y/wa") //GTPConseil12345
                ->setRoles(["ROLE_ADMIN"]);
            return $user;
        });

        $manager->flush();
    }
}
