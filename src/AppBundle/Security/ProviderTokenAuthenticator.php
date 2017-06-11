<?php

namespace AppBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\RouterInterface;

use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\Role;
use AppBundle\Exception\AuthenticationRequiredException;
use AppBundle\Exception\TokenIsNotSetException;
use AppBundle\Exception\TokenNotExistException;
use AppBundle\Exception\UserAuthenticationException;

/**
 * Class ProviderTokenAuthenticator
 * @package AppBundle\Security
 */
class ProviderTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ProviderTokenAuthenticator constructor.
     * @param EntityManager $em
     * @param RouterInterface $router
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, RouterInterface $router, ContainerInterface $container)
    {
        $this->em        = $em;
        $this->router    = $router;
        $this->container = $container;
    }

    /**
     * Get service from container by service id
     * @param string $serviceId
     *
     * @return object service
     */
    public function get($serviceId)
    {
        return $this->container->get($serviceId);
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function getCredentials(Request $request)
    {
        $tokenHeader = $request->headers->get('token');
        if (empty($tTokenHeader))
            throw new TokenIsNotSetException('Token is not undefined');

        return ($tokenHeader) ? ['token' => $tokenHeader] : null;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $tokenProvider
     * @return null
     */
    public function getUser($credentials, UserProviderInterface $tokenProvider)
    {
        $apiKey = $credentials['token'];
        $token  = $tokenProvider->loadUserByUsername($apiKey);

        if (!$token)
            throw new TokenNotExistException('Invalid credentials');

        return $token->getUser();
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$user->getRoles()->contains($this->em->getRepository('AppBundle:Role')->findOneBy(['role' => Role::ROLE_PROVIDER])))
            throw new UserAuthenticationException('User isn\'t allowed to use service');

        return true;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     *
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return;
    }

    /**
     * @return null
     */
    public function supportsRememberMe()
    {
        return;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new AuthenticationRequiredException('Authentication required');
    }
}