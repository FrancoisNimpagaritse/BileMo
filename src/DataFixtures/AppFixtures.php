<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * Encoder of users passwords
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        //We manage Client
        $client = new Client();
        $client->setName("Europe Galaxy Phones");

        $manager->persist($client);

        //We manage users
        for($i=0; $i<3; $i++) {
            $user = new User();

            $hash = $this->encoder->encodePassword($user, "password");
            
            $user->setName($faker->firstName . ' ' . $faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($hash)
                ->setClient($client);
                
            $manager->persist($user);
        }

        //We manage products
        for($i=0; $i<20; $i++) {
        $product = new Product();
        $product->setName($faker->sentence(2))
                ->setPrice($faker->randomFloat(2,100,1000))
                ->setDescription($faker->sentence(10));

        $manager->persist($product);
            
    }

        $manager->flush();
    }
}
