<?php

namespace App\DataFixtures\Articles;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Articles\Circuit;
use App\Entity\Articles\Category;
use Faker\Factory;

class CircuitFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        // Initialiser Faker
        $faker = Factory::create('fr_FR');
    
        // Créer 50 circuits
        for ($i = 0; $i < 50; $i++) {
            // Charger plusieurs catégories existantes pour chaque circuit
            /** @var Category $category */
            $category = $this->getReference('category_circuits_' . ($i % 5), Category::class);
    
            if (!$category instanceof Category) {
                throw new \LogicException('La référence "category_circuits_' . ($i % 5) . '" doit être une instance de Category.');
            }
    
            $circuit = new Circuit();
            $circuit->setName($faker->sentence(3))
                    ->setDescription($faker->paragraph)
                    ->setPrice($faker->randomFloat(2, 50, 500))
                    ->setAvailability($faker->boolean)
                    ->setDuration($faker->numberBetween(1, 14))
                    ->setState($faker->boolean)
                    ->setSlug($faker->unique()->slug);
    
            // Ajouter la catégorie au circuit
            $circuit->addCategory($category);
    
            // Persister l'objet Circuit
            $manager->persist($circuit);
        }
    
        // Sauvegarder tous les circuits dans la base de données
        $manager->flush();
    }
    
}
