<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ClientRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private ClientRepository $clientRepository
    ) {
    }

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 15; ++$i) {
            $product = new Product();
            $product->setName('Product ' . $i);
            $product->setPrice(random_int(10, 100));
            $product->setDescription('Super produit');
            $product->setColor(random_int(0, 1) ? 'red' : 'blue');
            $product->setSar(random_int(10, 100) / 100);
            $product->setStorage('128GO');

            $manager->persist($product);
            $manager->flush();
        }

        for ($i = 1; $i <= 3; ++$i) {
            $client = new Client();
            $client->setName('Client ' . $i);
            $client->setEmail('client' . $i . '@test.fr');
            $client->setPassword($this->passwordHasher->hashPassword($client, 'test'));
            $client->setName('Client ' . $i);
            $client->setAddress('1 rue de la paix');
            $client->setZipCode('75000');
            $client->setCity('Paris');

            $manager->persist($client);
            $manager->flush();
        }

        for ($j = 1; $j <= 3; ++$j) {
            $user = new User();
            $user->setEmail('user' . $j . '@test.fr');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'test'));
            $user->setFirstname('User ' . $j);
            $user->setLastname('Test');
            $user->setClient($this->clientRepository->findOneBy(['name' => 'Client ' . $j]));

            $manager->persist($user);
            $manager->flush();
        }
    }
}
