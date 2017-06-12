<?php

// src/BusinessBundle/DataFixtures/ORM/LoadBusinessData.php
namespace BusinessBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\Business;

class LoadBusinessData extends AbstractFixture implements OrderedFixtureInterface
{
    const ORDER = 3;

    public function load(ObjectManager $manager)
    {
        $business = new Business();

        $business->setUser($this->getReference('provider'));
        $business->setTitle('Business Title');
        $business->setDescription('Business Description');

        $manager->persist($business);
        $manager->flush();

        $this->addReference('business', $business);
    }

    public function getOrder()
    {
        return self::ORDER;
    }
}