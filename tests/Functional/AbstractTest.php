<?php

namespace App\Tests\Functional;

use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractTest extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @throws JsonException
     */
    protected function createAuthenticatedClient(): KernelBrowser
    {
        $this->client->request(
            'POST',
            '/api/login',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"email":"pierre.thiollent76@gmail.com","password":"test"}'
        );

        $data = json_decode(
            $this->client->getResponse()->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->client->setServerParameter('HTTP_Authorization', "Bearer {$data['token']}");

        return $this->client;
    }
}
