<?php

namespace ProductBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Guzzle\Http\Client;
use GuzzleHttp\Client as ClientAsync;

/**
 * Class StoreService
 * @package ProductBundle\Service
 */
class StoreService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ClientAsync
     */
    private $clientAsync;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Router
     */
    private $router;

    /**
     * StoreService constructor.
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
        $this->clientAsync  = new ClientAsync(['base_uri' => 'http://' . trim($host, '/')]);
    }

    /**
     * Get store provider info
     * @param int $storeId
     *
     * @return array|null
     */
    public function getStoreProvider($storeId)
    {
        $request = $this->client->get($this->router->generate('store_get_provider', array('id' => $storeId)));
        $request->setHeader('token', $this->tokenStorage->getToken()->getUser()->getTokens()->first()->getToken());

        try {

            $response = $request->send();
            return $response->json();

        } catch (\Exception $e) {

            return null;
        }
    }

    /**
     * Update product date. THIS METHOD SHOULD UPDATE STORES IN ASYNC MODE
     * @param int $storeId
     *
     * @return array|null
     */
    public function updateProductDate($storeId)
    {
        try {

            $this->clientAsync->request(
                'PUT',
                $this->router->generate('store_update_product_date', array('id' => $storeId)),
                ['headers' => ['token' => $this->tokenStorage->getToken()->getUser()->getTokens()->first()->getToken()]]);

            return true;

        } catch (\Exception $e) {

            return null;
        }
    }
}