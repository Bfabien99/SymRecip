<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($faker->word())
                ->setPrice(mt_rand(0,199));

            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
