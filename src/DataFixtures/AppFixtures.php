<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator  $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // Create 20 ingredients
        for ($i = 0; $i < 20; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word());
            $ingredient->setPrice(mt_rand(0, 100) );

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // Create 20 recipes
        for ($i = 0; $i < 20; $i++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word());
            $recipe->setTime(mt_rand(10, 120));
            $recipe->setPersonsNbr(mt_rand(1, 10)); 
            $recipe->setDifficulty(mt_rand(1, 5));
            $recipe->setDescription($this->faker->paragraph(3));
            $recipe->setPrice(mt_rand(0, 50));
            $recipe->setIsFavorite($this->faker->boolean());

            // Add between 5 and 15 ingredients to each recipe
            for ($k = 0; $k < mt_rand(5, 15); $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }



            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
