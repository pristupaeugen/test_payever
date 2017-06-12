<?php

// src/ProviderBundle/DataFixtures/ORM/LoadProviderData.php
namespace ProviderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use AppBundle\Entity\User;
use AppBundle\Entity\Token;
use AppBundle\Entity\Role;

class LoadProviderData extends AbstractFixture implements OrderedFixtureInterface
{
    const TOKEN = 'provider_token';
    const ORDER = 2;

    public function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setRole(Role::ROLE_PROVIDER);

        $manager->persist($role);
        $manager->flush();

        $provider = new User();

        $provider->setFirstname('Provider firstname');
        $provider->setSurname('Provider surname');
        $provider->setEmail('provider@test.com');
        $provider->setUsername('provider@test.com');
        $provider->setPassword('secret');

        $provider->addRole($manager->getRepository('AppBundle:Role')->findOneBy(['role' => Role::ROLE_PROVIDER]));

        $manager->persist($provider);
        $manager->flush();

        $token = new Token();
        $token->setToken(LoadProviderData::TOKEN);
        $token->setUser($provider);
        $provider->addToken($token);

        $manager->persist($provider);
        $manager->persist($token);

        $manager->flush();

        $this->addReference('provider', $provider);
    }

    public function getOrder()
    {
        return LoadProviderData::ORDER;
    }
}