<?php

namespace App\Tests\Functional;

class ProductsTest extends AbstractTest
{
    public function testGetAllProductsWithoutJWTShouldReturn401(): void
    {
        $this->client->request('GET', '/api/products');
        self::assertResponseStatusCodeSame(401);
    }

    /**
     * @throws \JsonException
     */
    public function testGetAllProductsShouldReturn200AndArrayOfProducts(): void
    {
        $this->client = $this->createAuthenticatedClient();
        $this->client->request('GET', '/api/products');

        $response = $this->client->getResponse();
        self::assertResponseIsSuccessful();
        self::assertJson($response->getContent());

        $products = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($products['hydra:member']);
        self::assertCount(10, $products['hydra:member']);

        foreach ($products['hydra:member'] as $product) {
            self::assertArrayHasKey('id', $product);
            self::assertArrayHasKey('name', $product);
            self::assertArrayHasKey('price', $product);
            self::assertArrayHasKey('description', $product);
            self::assertArrayHasKey('color', $product);
            self::assertArrayHasKey('sar', $product);
            self::assertArrayHasKey('storage', $product);
        }
    }

    /**
     * @throws \JsonException
     */
    public function testGetAllProductsShouldReturnJsonLd(): void
    {
        $this->client = $this->createAuthenticatedClient();
        $this->client->request('GET', '/api/products');

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
}
