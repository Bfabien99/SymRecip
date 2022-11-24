<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        #ingredient fixtures
        $ingredients = [];
        for ($i = 1; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($faker->word())
                ->setPrice(mt_rand(0,199));

            $ingredients []= $ingredient;
            $manager->persist($ingredient);
        }

        #recipe fixtures
        for ($j = 1; $j <= 25; $j++) {
            $recipe = new Recipe();
            $recipe->setName($faker->word())
                ->setTime(mt_rand(0,1) == 1 ? mt_rand(1,1440) : null)
                ->setNbPeople(mt_rand(0,1) == 1 ? mt_rand(1,50) : null)
                ->setDifficulty(mt_rand(0,1) == 1 ? mt_rand(1,5) : null)
                ->setDescription($faker->text(300))
                ->setPrice(mt_rand(0,1) == 1 ? mt_rand(1,1000) : null)
                ->setIsFavorite(mt_rand(0,1) == 1 ? true : false);
            
            for ($k=0; $k <= mt_rand(5,15) ; $k++) { 
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients)-1)]);
            }

            $manager->persist($recipe);
        }
        
        $manager->flush();
    }
}
