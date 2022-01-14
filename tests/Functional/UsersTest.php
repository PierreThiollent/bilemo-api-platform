<?php

namespace App\Tests\Functional;

class UsersTest extends AbstractTest
{
    public function testGetUserWithoutTokenShouldReturn401(): void
    {
        $this->client->request('GET', '/api/users');
        self::assertResponseStatusCodeSame(401);
    }

    /**
     * @throws \JsonException
     */
    public function testGetUsersShouldReturn200AndArrayOfClientUsers(): void
    {
        $this->client = $this->createAuthenticatedClient();
        $this->client->request('GET', '/api/users');

        self::assertResponseStatusCodeSame(200);
        self::assertJson($this->client->getResponse()->getContent());

        $users = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($users['hydra:member']);
        self::assertCount(1, $users['hydra:member']);

        foreach ($users['hydra:member'] as $user) {
            self::assertArrayHasKey('id', $user);
            self::assertArrayHasKey('email', $user);
            self::assertArrayHasKey('firstname', $user);
            self::assertArrayHasKey('lastname', $user);
        }
    }

    /**
     * @throws \JsonException
     */
    public function testGetUsersShouldReturn200AndJsonLd(): void
    {
        $this->client = $this->createAuthenticatedClient();
        $this->client->request('GET', '/api/users');

        self::assertResponseStatusCodeSame(200);
        self::assertJson($this->client->getResponse()->getContent());

        $response = $this->client->getResponse();
        self::assertResponseIsSuccessful();
        self::assertJson($response->getContent());

        $response = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($response);
        self::assertArrayHasKey('@context', $response);
        self::assertArrayHasKey('@type', $response);
        self::assertArrayHasKey('@id', $response);
        self::assertArrayHasKey('hydra:totalItems', $response);
    }

    public function testPostUserWithoutTokenShouldReturn401(): void
    {
        $this->client->request('POST', '/api/users');
        self::assertResponseStatusCodeSame(401);
    }

    /**
     * @throws \JsonException
     */
    public function testPostUserShouldReturn201AndUserData(): void
    {
        $this->client = $this->createAuthenticatedClient();
        $this->client->request(
            'POST',
            '/api/users',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"email": "user5@test.fr", "firstname": "User5", "lastname": "Test", "password": "test"}'
        );

        self::assertResponseStatusCodeSame(201);
        self::assertJson($this->client->getResponse()->getContent());

        $user = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('id', $user);
        self::assertArrayHasKey('email', $user);
        self::assertArrayHasKey('firstname', $user);
        self::assertArrayHasKey('lastname', $user);
        self::assertArrayHasKey('@context', $user);
        self::assertArrayHasKey('@type', $user);
        self::assertArrayHasKey('@id', $user);
    }

    /**
     * @throws \JsonException
     */
    public function testPostWrongUserDataShouldReturn422AndJsonLd(): void
    {
        $this->client = $this->createAuthenticatedClient();
        $this->client->request(
            'POST',
            '/api/users',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: '{"email": "user5@test.fr", "firstname": "User5", "lastname": "Test"}'
        );

        self::assertResponseStatusCodeSame(422);

        $response = $this->client->getResponse();
        $response = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('@context', $response);
        self::assertArrayHasKey('@type', $response);
        self::assertArrayHasKey('hydra:title', $response);
        self::assertArrayHasKey('hydra:description', $response);
        self::assertArrayHasKey('violations', $response);

        foreach ($response['violations'] as $violation) {
            self::assertArrayHasKey('propertyPath', $violation);
            self::assertArrayHasKey('message', $violation);
            self::assertArrayHasKey('code', $violation);
        }
    }
}
