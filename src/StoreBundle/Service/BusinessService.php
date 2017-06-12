<?php

namespace StoreBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Guzzle\Http\Client;

/**
 * Class BusinessService
 * @package StoreBundle\Service
 */
class BusinessService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Router
     */
    private $router;

    /**
     * BusinessService constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param Router $router
     * @param string $host
     */
    public function __construct(TokenStorageInterface $tokenStorage, Router $router, $host)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router       = $router;
        $this->client       = new Client('http://' . trim($host, '/'));
    }

    /**
     * Get business data
     * @param int $businessId
     *
     * @return array|null
     */
    public function getBusinessData($businessId)
    {
        $request = $this->client->get($this->router->generate('business_get', array('id' => $businessId)));
        $request->setHeader('token', $this->tokenStorage->getToken()->getUser()->getTokens()->first()->getToken());

        try {

            $response = $request->send();
            return $response->json();

        } catch (\Exception $e) {

            return null;
        }
    }
}