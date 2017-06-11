<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\User;
use AppBundle\Entity\Token;
use AppBundle\Service\PasswordManager;

/**
 * Class TokenManager
 * @package AppBundle\Service
 */
class TokenManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var PasswordManager
     */
    private $passwordManager;

    /**
     * TokenManager constructor.
     *
     * @param EntityManager $em
     * @param PasswordManager $passwordManager
     */
    public function __construct(EntityManager $em, PasswordManager $passwordManager)
    {
        $this->em              = $em;
        $this->passwordManager = $passwordManager;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function populateUser(User $user)
    {
        if (!count($user->getTokens())) {

            $tokenHeader = $this->passwordManager->generateStrongPassword(16);

            $token = new Token();
            $token->setToken($tokenHeader);

            $user->addToken($token);

            $this->em->persist($token);
            $this->em->persist($user);
            $this->em->flush();

            $token->setUser($user);
            $this->em->persist($token);
            $this->em->flush();
        }

        return true;
    }
}