<?php

namespace App\DataFixtures\Articles;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Articles\Circuit;
use App\Entity\Articles\Category;
use Faker\Factory;

class CircuitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Initialiser Faker
        $faker = Factory::create('fr_FR');

        // Référence à la première catégorie "Circuits" (index 0)
        $category = $this->getReference('category_circuits_0', Category::class);

        // Créer le premier circuit
        $circuit1 = new Circuit();
        $circuit1->setName('Circuit des châteaux')
                 ->setDescription('Explorez les châteaux historiques.')
                 ->setCategory($category)
                 ->setPrice($faker->randomFloat(2, 50, 500))
                 ->setAvailability($faker->boolean)
                 ->setDuration($faker->numberBetween(1, 14))
                 ->setState($faker->boolean)
                 ->setSlug($faker->unique()->slug);
                 
        $manager->persist($circuit1);

        // Créer le deuxième circuit
        $circuit2 = new Circuit();
        $circuit2->setName('Circuit gastronomique')
                 ->setDescription('Dégustez les spécialités locales.')
                 ->setCategory($category)
                 ->setPrice($faker->randomFloat(2, 50, 500))
                 ->setAvailability($faker->boolean)
                 ->setState($faker->boolean)
                 ->setDuration($faker->numberBetween(1, 14)); 

        $manager->persist($circuit2);

        // Enregistrer les circuits en base de données
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixtures::class]; // Assurez-vous que CategoryFixtures est chargé avant
    }
}