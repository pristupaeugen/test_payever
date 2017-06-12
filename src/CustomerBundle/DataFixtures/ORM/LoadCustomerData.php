<?php

// src/CustomerBundle/DataFixtures/ORM/LoadCustomerData.php
namespace CustomerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\User;
use AppBundle\Entity\Token;
use AppBundle\Entity\Role;

class LoadCustomerData extends AbstractFixture implements OrderedFixtureInterface
{
    const TOKEN = 'customer_token';
    const ORDER = 1;

    public function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setRole(Role::ROLE_CUSTOMER);

        $manager->persist($role);
        $manager->flush();

        $customer = new User();

        $customer->setFirstname('Customer firstname');
        $customer->setSurname('Customer surname');
        $customer->setEmail('customer@test.com');
        $customer->setUsername('customer@test.com');
        $customer->setPassword('secret');

        $customer->addRole($manager->getRepository('AppBundle:Role')->findOneBy(['role' => Role::ROLE_CUSTOMER]));

        $manager->persist($customer);
        $manager->flush();

        $token = new Token();
        $token->setToken(LoadCustomerData::TOKEN);
        $token->setUser($customer);
        $customer->addToken($token);

        $manager->persist($customer);
        $manager->persist($token);

        $manager->flush();
    }

    public function getOrder()
    {
        return LoadCustomerData::ORDER;
    }
}