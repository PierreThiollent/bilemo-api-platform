<?php

namespace App\Tests\Functional;

class AuthTest extends AbstractTest
{
    /**
     * @throws \JsonException
     */
    public function testAuthWithWrongCredentialsShouldReturn401(): void
    {
        $this->client->request(
            'POST',
            '/api/login',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"email":"admin","password":"admin"}'
        );

        self::assertResponseStatusCodeSame(401);
        $response = $this->client->getResponse();
        self::assertJson($response->getContent());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('code', $data);
        self::assertArrayHasKey('message', $data);

        self::assertEquals('Invalid credentials.', $data['message']);
    }

    /**
     * @throws \JsonException
     */
    public function testAuthWithCorrectCredentialsShouldReturn200(): void
    {
        $this->client->request(
            'POST',
            '/api/login',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"email":"client1@test.fr","password":"test"}'
        );

        self::assertResponseStatusCodeSame(200);
        $response = $this->client->getResponse();
        self::assertJson($response->getContent());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('token', $data);
    }
}
