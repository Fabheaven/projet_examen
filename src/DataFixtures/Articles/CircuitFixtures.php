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

        // Créer 50 circuits
        for ($i = 0; $i < 50; $i++) {
            $circuit = new Circuit();
            $circuit->setName($faker->sentence(3)) // Nom aléatoire
                    ->setDescription($faker->paragraph) // Description aléatoire
                    ->setCategory($category)
                    ->setPrice($faker->randomFloat(2, 50, 500)) // Prix aléatoire entre 50 et 500
                    ->setAvailability($faker->boolean) // Disponibilité aléatoire
                    ->setDuration($faker->numberBetween(1, 14)) // Durée aléatoire entre 1 et 14 jours
                    ->setState($faker->boolean) // État aléatoire (true/false)
                    ->setSlug($faker->unique()->slug); // Slug unique

            // Persister le circuit
            $manager->persist($circuit);
        }

        // Enregistrer les circuits en base de données
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixtures::class]; // Assurez-vous que CategoryFixtures est chargé avant
    }
}