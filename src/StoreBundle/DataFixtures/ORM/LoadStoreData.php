<?php

// src/StoreBundle/DataFixtures/ORM/LoadStoreData.php
namespace StoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\Store;

class LoadStoreData extends AbstractFixture implements OrderedFixtureInterface
{
    const ORDER = 4;

    public function load(ObjectManager $manager)
    {
        $store1 = new Store();

        $store1->setBusiness($this->getReference('business'));
        $store1->setTitle('Store Title 1');
        $store1->setDescription('Store Description 1');

        $store2 = new Store();

        $store2->setBusiness($this->getReference('business'));
        $store2->setTitle('Store Title 2');
        $store2->setDescription('Store Description 2');

        $manager->persist($store1);
        $manager->persist($store2);

        $manager->flush();
    }

    public function getOrder()
    {
        return self::ORDER;
    }
}