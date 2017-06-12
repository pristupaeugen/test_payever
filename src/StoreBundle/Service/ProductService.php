<?php

namespace StoreBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Guzzle\Http\Client;

/**
 * Class ProductService
 * @package StoreBundle\Service
 */
class ProductService
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
     * Delete many products
     * @param int $storeId
     *
     * @return true|null
     */
    public function deleteManyProducts($storeId)
    {
        $request = $this->client->delete($this->router->generate('product_delete_many', array('store' => $storeId)));
        $request->setHeader('token', $this->tokenStorage->getToken()->getUser()->getTokens()->first()->getToken());

        try {

            $request->send();
            return true;

        } catch (\Exception $e) {

            return null;
        }
    }
}