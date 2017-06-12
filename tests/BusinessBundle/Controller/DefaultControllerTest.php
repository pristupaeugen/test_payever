<?php

namespace BusinessBundle\Tests\Controller;

use Tests\AppBundle\AppTestCase;

class DefaultControllerTest extends AppTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $businessId = 2;

        $client->request('GET', "/business/$businessId", [], [], ['HTTP_token' => 'provider_token']);
        $this->assertEquals(
            404,
            $client->getResponse()->getStatusCode()
        );

        $businessId = 1;

        $client->request('GET', "/business/$businessId");
        $this->assertEquals(
            401,
            $client->getResponse()->getStatusCode()
        );

        $client->request('GET', "/business/$businessId", [], [], ['HTTP_token' => 'provider_token']);
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        $data = json_decode($client->getInternalResponse()->getContent(), true);
        $this->assertEquals($businessId, $data['id']);
    }

    public function testCreate()
    {
        $client = static::createClient();

        $client->request('POST', "/business/", [], [],
                         ['HTTP_token' => 'provider_token'],
                         '{"title":"New test business", "description":"New Test description"}');

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        $client->request('POST', "/business/", [], [],
            ['HTTP_token' => 'provider_token'],
            '{"title":"", "description":"New Test description"}');

        $this->assertEquals(
            400,
            $client->getResponse()->getStatusCode()
        );

        $client->request('POST', "/business/", [], [], [], '{"title":"", "description":"New Test description"}');

        $this->assertEquals(
            401,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $client->request('PUT', "/business/1", [], [],
            ['HTTP_token' => 'provider_token'],
            '{"title":"New test business", "description":"New Test description"}');

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );

        $client->request('PUT', "/business/2", [], [],
            ['HTTP_token' => 'provider_token'],
            '{"title":"New test business", "description":"New Test description"}');

        $this->assertEquals(
            404,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testDelete()
    {
        $client = static::createClient();

        $client->request('DELETE', "/business/1", [], [], ['HTTP_token' => 'provider_token']);

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
    }
}
