<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Group;

class LoadGroupData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $group = new Group();
        $group->setName('group1');

        $manager->persist($group);
        $manager->flush();
    }
}