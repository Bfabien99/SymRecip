<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    # Pour hacher le mot de passe
    // private UserPasswordHasherInterface $hasher;

    // public function __construct(UserPasswordHasherInterface $hasher){
    //     $this->hasher = $hasher;
    // }

    public function load(ObjectManager $manager):void
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
                ->setDescription($faker->paragraphs(mt_rand(3,5), true))
                ->setPrice(mt_rand(0,1) == 1 ? mt_rand(1,1000) : null)
                ->setIsFavorite(mt_rand(0,1) == 1 ? true : false);
            
            for ($k=0; $k <= mt_rand(5,15) ; $k++) { 
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients)-1)]);
            }

            $manager->persist($recipe);
        }

        #user fixtures
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setFullName($faker->firstname() .' '.$faker->name())
                ->setPseudo($faker->lastname())
                ->setEmail($faker->email())
                ->setPlainPassword('password')
                ->setRoles(['ROLE_USER']);

            // $plainPassword = 'password';
            // $hashedPass = $this->hasher->hashPassword($user, $plainPassword);
            // $user->setPassword($hashedPass);

            $manager->persist($user);
        }
        
        $manager->flush();
    }
}
